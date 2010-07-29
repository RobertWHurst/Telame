<?php
class IncluderComponent extends Object {

	var $include_array = array(
		'css' => array(),
		'script' => array()
	);
		
	function add($type, $file){
	
		//if we're adding css		
		if($type == 'css'){
			if(is_array($file)){
				foreach($file as $_file)
					$this->include_array['css'][] = "{$_file}.css";
			}
			else
				$this->include_array['css'][] = "{$file}.css";
				
		}
		
		//if we're adding a script
		else if($type == 'script'){
			if(is_array($file)){
				foreach($file as $_file)
					$this->include_array['script'][] = "{$_file}.js";
			}
			else
				$this->include_array['script'][] = "{$file}.js";			
		}
	}
	
    function script() {
    	return $this->include_array['script'];
    }
	
    function css() {
    	return $this->include_array['css'];
    }
}