<?php
/**
 * This class builds an image tag. The main purpose of this is to get the image dimensions and
 * include the appropriate attributes if not specified. This will improve front end performance.
 *
 * @author Seth Cardoza <seth.cardoza@gmail.com>
 * @category image
 * @package helper
 */
class HtmlImageHelper extends AppHelper
{
	var $helpers = array('Html');

	/**
	 * Builds html img tag determining width and height if not specified in the
	 * attributes parameter.
	 *
	 * @param string $src relative path to image including the 'img' directory
	 * @param array $options array of html attributes to apply to the image
	 *
	 * @access public
	 *
	 * @return no return value, outputs the img tag
	 */
	public function image($src, $options = array()) {
		//get width/height via exif data
		//build image html
		if(file_exists(WWW_ROOT . 'img' . DS . $src)) {
			$image_size = getimagesize(WWW_ROOT . 'img' . DS . $src);
			if(!array_key_exists('width', $options) && array_key_exists('height', $options)) {
				$options['width'] = ($image_size[0] * $options['height']) / $image_size[1];
			} elseif(array_key_exists('width', $options) && !array_key_exists('height', $options)) {
				$options['height'] = ($image_size[1] * $options['width']) / $image_size[0];
			} else {
				$options['width'] = $image_size[0];
				$options['height'] = $image_size[1];
			}
		}
		if (isset($options['static']) && $options['static']) {
			$src = 'http://' . Configure::read('StaticDomain') . DS . 'img' . DS . $src;
			unset($options['static']);
		}
		return $this->Html->image($src, $options);
	}
}