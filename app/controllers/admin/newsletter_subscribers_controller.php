<?php
class NewsletterSubscribersController extends AdminController {
	function index(){
		$this->page_title = _("Newsletter Subscribers");

		$this->sorting->add("created_at",array("reverse" => true));
		$this->sorting->add("email","LOWER(email)");
		$this->sorting->add("name","COALESCE(LOWER(name),'')");
		$this->sorting->add("id");

		($d = $this->form->validate($this->params)) || ($d = $this->form->get_initial());
		$conditions = $bind_ar = array();

		if($d["search"]){
			$conditions[] = "UPPER(id||' '||' '||COALESCE(name,'')||' '||COALESCE(email,'')) LIKE UPPER('%'||:search||'%')";
			$bind_ar[":search"] = $d["search"];
		}

		// building URL for CSV export
		$params = $this->params->toArray();
		unset($params["offset"]);
		$params["format"] = "csv";
		$this->tpl_data["csv_export_url"] = $this->_link_to($params);

		// finding records
		$this->tpl_data["finder"] = $finder = NewsletterSubscriber::Finder(array(
			"conditions" => $conditions,
			"bind_ar" => $bind_ar,
			"order_by" => $this->sorting,
			"offset" => $this->params->getInt("offset"),
			"limit" => $this->params->getString("format")=="csv" ? null : 50,
		));

		// providing CSV export
		if($this->params->getString("format")=="csv"){
			$this->response->setContentType("text/plain");
			$this->response->setHeader('Content-Disposition: inline; filename="emails.txt"');
			$this->render_template = false;
			foreach($finder->getRecords() as $r){
				$this->response->writeln($r->getEmail());
			}
		}
	}

	function destroy(){
		$this->_destroy($this->newsletter_subscriber);
	}

	function _before_filter(){
		$this->action=="destroy" && $this->_find("newsletter_subscriber");
	}
}
