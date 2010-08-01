<?php
class AppModel extends Model {

	//this function takes an array of defaults and an array of agruments
	//the agruments array can contain any number of things. if an option
	//does not exist in the defaults array it is skipped and is not passed
	//on. the function will return a merged set of options and arguments.
	//aguments always override defaults. both the defaults array and the
	//aguments array can be multi dementional and have any depth.
	function parseArguments($defaults, $arguments) {

		if(!is_array($defaults) || !is_array($arguments))
			return $defaults; //just return the defaults (something goofed)

		//copy the defaults
		$results = $defaults;

		foreach($arguments as $key => $argument){

			//if the agrument is invalid contine the loop
			if(!isset($defaults[$key]))
				continue; //the option is invalid

			//if the agrument is acually an array of aguments
			if(is_array($argument)){

				//if the agument is an array then make sure it is valid
				if(!is_array($defaults[$key]))
					continue; //the option is not an array

				//set the suboptions
				$subdefaults = $defaults[$key];

				$results[$key] = $this->parseArguments($subdefaults, $argument);
			} else {
				//just set it
				$results[$key] = $argument;
			}
		}
		return $results;
	}

}