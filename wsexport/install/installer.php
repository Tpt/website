<?php
/**
* @package   wsexport
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

require_once (dirname(__FILE__).'/../application.init.php');

jApp::setEnv('install');

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();
