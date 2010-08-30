<?php
App::import('Vendor', 'php_markdown', array('file' => 'php_markdown/markdown.php'));

class MarkdownHelper extends AppHelper {

	var $helpers = array('Text');

	function parse($text) {
		/**
		 * The cakePHP autolink method breaks markup syntax for url
		 * retated elements such as images.
		 * I believe the best solution is to use the old regular expression
		 * created to convert links before autolink was implemented.
		 * It will only convert urls surounded by whitespace to links.
		 *
		 * return $this->output($this->Text->autoLink(Markdown($text)));
		 */
		 
		 //format the content with markdown
		 $text = preg_replace('/ ((?:http[s]*|[s]*ftp|git):\/\/[^\s]+) /', ' <a href="$1" rel="" target="TELAME_LINK">$1</a> ', $text);
		 
		 //convert urls to links
		 $text = Markdown($text);
		 
		 return $this->output($text);
	}
}
?>