<?php
/**
* @package   wsexport
* @subpackage 
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

require ('../application.init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

checkAppOpened();

$config_file = 'index/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());


