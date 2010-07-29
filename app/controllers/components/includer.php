<?php
class IncluderComponent extends Object {

	var $includes = array(
		'css' => array(),
		'script' => array()
	);
		
	function add($type, $file){
	
		//if we're adding css		
		if($type == 'css'){
			if(is_array($file)){
				foreach($file as $_file)
					$this->includes['css'][] = "{$_file}.css";
			}
			else
				$this->includes['css'][] = "{$file}.css";
				
		}
		
		//if we're adding a script
		else if($type == 'script'){
			if(is_array($file)){
				foreach($file as $_file)
					$this->includes['script'][] = "{$_file}.js";
			}
			else
				$this->includes['script'][] = "{$file}.js";			
		}
	}
	
    function set() {
    	foreach($this->includes as $file_type => $include_type){
			$this->set("{$file_type}_for_layout", $include_type);
		}
    }
}