<?php
App::import('Vendor', 'php_markdown', array('file' => 'php_markdown/markdown.php'));

class MarkdownHelper extends AppHelper {
	function parse($text) {
		return $this->output(Markdown($text));
	}
}
?>