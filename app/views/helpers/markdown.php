<?php
App::import('Vendor', 'php_markdown', array('file' => 'php_markdown/markdown.php'));

class MarkdownHelper extends AppHelper {

	var $helpers = array('Text');

	function parse($text) {
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

		$emoticons = $this->Emoticon->find('all');

	    foreach($emoticons as $emoticon) {
			$text = str_ireplace($emoticon['Emoticon']['code'], '<img alt="' . $emoticon['Emoticon']['code'] . '" src="/img/icons/' . $emoticon['Emoticon']['name'] . '" />', $text);
	    }
	    return $text;
	}

}
?>