<?php 
class AppModel extends Model {
	var $actsAs = array('Containable');

	function validateSingle($field, $value = null) {
		$this->validate = array($field => $this->validate[$field]);
		$this->data[$this->alias][$field] = $value;
		return $this->validates();
	}
}