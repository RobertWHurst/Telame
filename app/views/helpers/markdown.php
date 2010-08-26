<?php
App::import('Vendor', 'php_markdown', array('file' => 'php_markdown/markdown.php'));

class MarkdownHelper extends AppHelper {

	var $helpers = array('Text');

	function parse($text) {
		return $this->output($this->Text->autoLink(Markdown($text)));
	}
}
?>