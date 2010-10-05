<?php
App::import('Vendor', 'php_markdown', array('file' => 'php_markdown/markdown.php'));

class MarkdownHelper extends AppHelper {

	var $helpers = array('HtmlImage', 'Text');

	function parse($text) {
		// Clean the text.  This will need testing for XSS and such
		$text = Sanitize::clean($text, array('encode' => false, 'remove_html' => true));

		// match links and format
		$text = preg_replace('/[\s]((?:(?:(?:http[s]*|[s]*ftp|git|svn):\/\/)|(?:mailto))[^\s]+)/', ' <a href="$1" rel="" target="_blank">$1</a> ', $text);

		// add emoticons
		$text = $this->emoticons($text);

		// run it though markdown
		$text = Markdown($text);

		return $this->output($text);
	}

	function emoticons($text) {
		App::Import('Model', 'emoticon');
		$this->Emoticon = new Emoticon;

		$emoticons = $this->Emoticon->find('all', array('order' => 'code DESC', 'cache' => 'emoticons'));

	    foreach($emoticons as $emoticon) {
	    	// arrays are used in str_ireplace for speed, even though it's a single search
			$text = str_ireplace(array($emoticon['Emoticon']['code']), array($this->HtmlImage->image('icons/' . $emoticon['Emoticon']['name'], array('static' => true))), $text);
	    }
	    return $text;
	}

}
?>