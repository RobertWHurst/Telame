<?php
App::import('Vendor', 'php_markdown', array('file' => 'php_markdown/markdown.php'));

class MarkdownHelper extends AppHelper {

	var $helpers = array('Text');

	function parse($text) {
		// Clean the text.  This will need testing for XSS and such
		$text = Sanitize::clean($text, array('encode' => false, 'remove_html' => true));

		// match links and format
		$text = preg_replace('/ ((?:http[s]*|[s]*ftp|git):\/\/[^\s]+)/', ' <a href="$1" rel="" target="_blank">$1</a> ', $text);

		// add emoticons
		$text = $this->emoticons($text);

		// run it though markdown
		$text = Markdown($text);

		return $this->output($text);
	}

	function emoticons($text) {
		App::Import('Model', 'emoticon');
		$this->Emoticon = new Emoticon;

		$emoticons = $this->Emoticon->find('all', array('order' => 'code DESC'));

	    foreach($emoticons as $emoticon) {
	    	// arrays are used in str_ireplace for speed, even though it's a single search
			$text = str_ireplace(array($emoticon['Emoticon']['code']), array(' <img alt="" src="/img/icons/' . $emoticon['Emoticon']['name'] . '" />'), $text);
	    }
	    return $text;
	}

}
?>