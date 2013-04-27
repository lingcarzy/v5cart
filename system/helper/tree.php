<?php

class Tree {
	private $arr;
	private $id;
		
	function __construct($arr, $id) {
		$this->arr = $arr;
		$this->id = $id;
	}
	
	function get_plane($pid = 0, $level = 0) {
		$parent = $this->get_children($pid, $level);
		$plane = array();
		$level++;
		foreach($parent as $p) {
			$children = $this->get_plane($p[$this->id], $level);
			if (empty($children)) $p['_leaf'] = 1;
			else $p['_leaf'] = 0;
			$plane[] = $p;			
			$plane = array_merge($plane, $children);
		}
		return $plane;
	}
	
	function get_tree($pid = 0, $level = 0) {
		$tree = $this->get_children($pid, $level);
		$level++;
		foreach ($tree as $idx => $item) {
			$tree[$idx]['children'] = $this->get_tree($item[$this->id], $level);
			if (empty($tree[$idx]['children'])) {
				$tree[$idx]['_leaf'] = 1;
			}
			else {
				$tree[$idx]['_leaf'] = 0;
			}
		}
		return $tree;
	}	
	
	function get_children($pid, $level = 0) {
		$children = array();
		foreach ($this->arr as $item) {
			if ($item['parent_id'] == $pid) {
				$item['_level'] = $level;
				$children[] = $item;
			}
		}
		return $children;
	}
}