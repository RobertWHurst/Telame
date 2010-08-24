<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

include(APP . DS . 'config' . DS . 'my_config' . DS . 'config.php');

//this function takes an array of defaults and an array of agruments
//the agruments array can contain any number of things. if an option
//does not exist in the defaults array it is skipped and is not passed
//on. the function will return a merged set of options and arguments.
//aguments always override defaults. both the defaults array and the
//aguments array can be multi dementional and have any depth.

function parseArguments($defaults, $arguments, $keep_unset = false) {
	if(!is_array($defaults) || !is_array($arguments))
		return $defaults; //just return the defaults (something goofed)
		
	//copy the defaults
	$results = $defaults;
	foreach($arguments as $key => $argument){
	
		//if the agrument is invalid contine the loop
		if(!$keep_unset && !isset($defaults[$key]))
			continue; //the option is invalid
			
		//if the agrument is acually an array of aguments
		if(is_array($argument)){
			//if keep_unset is true and the default is not an array add the array
			if($keep_unset && !is_array($defaults[$key])){
				$results[$key] = $argument;
				continue; //advance the loop
			}
			
			//if the agument is an array then make sure it is valid
			if(!is_array($defaults[$key]))
				continue; //the option is not an array
				
			//set the suboptions
			$subdefaults = $defaults[$key];
			$results[$key] = parseArguments($subdefaults, $argument, $keep_unset);
		} else {
		
			//just set it
			$results[$key] = $argument;
		}
	}
	
	return $results;
}

function arrayMergeContained($array_of_arrays_to_be_merged){
	
	//loop through and merge the arrays		
	foreach($array_of_arrays_to_be_merged as $array){
		
		//check to see if we have an array from last loop and merge it to the current if one does exist
		if(isset($_array)){
			$_array = array_merge_recursive($_array, $array);
		}
		else{
			//save the array to be merged with the next one on the following cycle
			$_array = $array;
		} 						
	}
	
	return $_array;
}
