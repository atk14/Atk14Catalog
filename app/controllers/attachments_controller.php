<?php
class AttachmentsController extends ApplicationController{

	function detail(){

		$class_name = String4::ToObject($this->controller)->singularize()->camelize()->toString(); // "attachments" -> "Attachment" or "files" -> "File"

		if(!$attachment = $class_name::GetInstanceByToken($this->params->getString("token"),array("hash_length" => 10, "extra_salt" => "detail"))){
			return $this->_execute_action("error404");
		}

		if($this->params->getString("filename")!=$attachment->getFilename()){
			// uzivatel ma link se starym nazvem prilohy
			return $this->_redirect_to($attachment->getUrl(),array("moved_permanently" => true));
		}

		$pupiq_url = $attachment->getUrl(true);

		$cache_dir = TEMP."/$this->controller/"; // TEMP."/attachment/" nebo TEMP."/files/"
		Files::MkDir($cache_dir);
		$cached_body_filename = $cache_dir.md5($pupiq_url)."_body";
		$cached_headers_filename = $cache_dir.md5($pupiq_url)."_headers";

		if(!file_exists($cached_body_filename) || !file_exists($cached_headers_filename)){
			$uf = new UrlFetcher($pupiq_url);
			if(!$uf->found()){
				//return $this->_execute_action("error500");
				throw new Exception("UrlFetcher error: url: $pupiq_url, status_code=".$uf->getStatusCode().", error_message=".$uf->getErrorMessage());
			}

			$headers = $uf->getResponseHeaders(array("as_hash" => true));

			$tmp_body = Files::GetTempFilename();
			$content = $uf->getContent();
			$content->writeToFile($tmp_body);
			$tmp_headers = Files::WriteToTemp(serialize($headers),$error,$error_str);

			if(!$tmp_body || !$tmp_headers){
				//return $this->_execute_action("error500");
				throw new Exception("Tmp file cannot be created ($error_str)");
			}

			if(!Files::MoveFile($tmp_body,$cached_body_filename,$error,$error_str) || !Files::MoveFile($tmp_headers,$cached_headers_filename,$error2,$error_str2)){
				//return $this->_execute_action("error500");
				throw new Exception("File cannot be moved ($error_str, $error_str2)");
			}
		}

		$this->render_template = false;

		$headers = unserialize(Files::GetFileContent($cached_headers_filename));

		foreach($headers as $header => $value){
			if(in_array(strtolower($header),array(
				"content-length",
				"last-modified",
				"content-type",
				"etag",
			))){
				$this->response->setHeader($header,$value);
			}
		}

		$this->response->buffer->addFile($cached_body_filename);
	}
}
