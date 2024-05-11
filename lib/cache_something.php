<?php
/**
 * $cache = new CacheSomething(function($ids, $options) {
 *    return array [ 'id' => ObjectSDanymId, 'id2' => ObjectSDanymId, ...... ]
 * })
 * $cache->get($objectId1);
 * >> Object1
 * Multiple objects, $objectId2 not exists
 * $cache->get([$objectId, $objectId2, ....]);
 * >> [ $objectId => Object1, $objectId2 => null, .... ]
 */

class CacheSomething {

	protected $function;
	protected $cache;
	protected $model;
	protected $options;

	function __construct($function, $model = False, $options = []) {
		$this->function = $function;
		$this->cache = [ null => null ];
		$this->model = $model;
		$this->options = $options + [
			'force_read' => false,
			'read_cached' => true,
			'map_id' => null,
			'map_method' => null,
			'clear_condition' => null
		];
		if($this->options['map_method']) {
			$this->options['map_id'] = function($ids) {
				$ids= Cache::Get($this->model, array_filter($ids, "is_numeric")) + $ids;
				//all is object now
				$map_method = $this->options['map_method'];
				return array_map(function($v) use($map_method)  {return $v?$v->$map_method():null; }, $ids);
			};
		}

		if($model) {
			$cacher = Cache::GetObjectCacher($model);
			if($cacher instanceof ExtendedObjectCacher) {
				$cacher->registerToClear($this);
			}
		}
	}

	function mapIds($ids) {
			if($this->options['map_id']) {
				$fce = $this->options['map_id'];
				$ids = $fce($ids);
			} else {
				$ids = Tablerecord::ObjToId($ids);
			}
			return $ids;
	}

	function clearCache($ids = null) {
		if($this->options['clear_condition'] && !$this->options['clear_condition']($ids)) {
			return;
		}
		if($ids === null) {
			$this->flushCache();
		} else {
			if(!is_array($ids)) {
				$ids = [ $ids ];
			} else {
				$ids = $ids;
			}
			$ids = $this->mapIds($ids);
			$this->cache = array_diff_key($this->cache, $ids) + [ null => null ];
		}
	}

	function flushCache() {
		$this->cache = [ null => null ];
	}

	function get($ids, $options = []) {
		$one = !is_array($ids);
		if($one) {
			$ids = [ $ids ];
		}

		$options += $this->options;
		$ids = $this->mapIds($ids);
		$keys = array_flip(array_filter($ids));

		if($options['force_read']) {
				$toRead = $keys;
		} else {
			  $toRead = array_diff_key($keys, $this->cache);
		}

		if($toRead) {
			if($this->model && $options['read_cached']) {
				$add = $this->mapIds(Cache::CachedIds($this->model));
				$toRead +=
					array_diff_key(
						array_flip(array_filter($add)),
						$this->cache
					);
			}
			$toRead = array_flip($toRead);
			$toRead = array_combine($toRead, $toRead);
			$fce = $this->function;
			$out = $fce($toRead, $options);
			$out += array_fill_keys($toRead, null);
			$this->cache = $out + $this->cache;
		}

		if($this->options['map_id']) {
			foreach($ids as &$id) {
					$id = $this->cache[$id];
			}
			$out = $ids;
		} else {
			$out = array_intersect_key($this->cache, $keys);
		}

		if( $one ) {
			$out = current($out);
		}

		return $out;
	}
}
