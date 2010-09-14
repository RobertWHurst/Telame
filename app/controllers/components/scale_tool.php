<?php
class ScaleToolComponent{

	//ver. 0.3

	//the mode that scale tool will operate
	//in.

	private $mode = 'fit'; // 'fill', 'fit'

	//the height and width of the box.

	private $box_height = 100;
	private $box_width = 100;

	//Sets the mode of scale tool.

	function setMode($mode){
		//Switches the operating mode of
		//'ScaleTool'.

		if($mode == 'fit' || $mode == 'fill')
			$this->mode = $mode;
	}

	//Sets a new height and width for the
	//box.

	function setBox($height, $width){
		//Takes the height and width passed and
		//saves them to the 'ScaleTool' object.

		$this->box_height = $height;
		$this->box_width = $width;
	}

	//Caclulate a new height and width that fills
	//or fits the box based on a subject height
	//and width.

	//Returns 'array('height' => x, 'width' => y)';

	function getNewSize($subject_height, $subject_width){
		//set defaults.

		$size_by_height = $size_by_width = $over_fill = false;
		$diff_height = $diff_width = 0;

		//gather settings.

		$box_height = $this->box_height;
		$box_width = $this->box_width;
		$mode = $this->mode;

		//calculate the difference between the box
		//and the subject so we can use it to
		//create new dementions for the subject.

		if($subject_height != $box_height)
			$diff_height = $subject_height - $box_height;
		if($subject_width != $box_width)
			$diff_width = $subject_width - $box_width;

		//if the height difference is greater
		//than the width difference.

		if($mode == 'fill') {
			if($diff_height < $diff_width){
				$size_by = 'height';
			}
			else{
				$size_by = 'width';
			}
		}
		else {
			if($diff_height > $diff_width){
				$size_by = 'height';
			}
			else {
				$size_by = 'width';
			}
		}

		//if diff height
		if($size_by == 'height'){
			$newSize['height'] = $subject_height - $diff_height;
			$newSize['width'] = round($newSize['height'] * $subject_width / $subject_height);
		}
		//if diff width
		elseif($size_by == 'width'){
			$newSize['width'] = $subject_width - $diff_width;
			$newSize['height'] = round($newSize['width'] * $subject_height / $subject_width);
		}

		return $newSize;
	}
}