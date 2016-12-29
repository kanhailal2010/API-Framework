<?php

// include all the required files

//inlcude the config file
require(dirname(__FILE__).'/../config/config.php');

// include functions file
require_once(dirname(__FILE__).'/function.php');

// include encrypt file
require_once(dirname(__FILE__).'/encrypt.php');

// include uri library
require_once( dirname(__FILE__).'/uri.php');

// include input library
require_once( dirname(__FILE__).'/input.php');

// include database library
require_once( dirname(__FILE__).'/mysqlidb.php');

// include the helper
require_once( dirname(__FILE__).'/helper.php');


// Autoload the libraries and initiate them
require_once( dirname(__FILE__).'/autoload.php');

// include the model
require_once( dirname(__FILE__).'/model.php');

// include the controller
require_once( dirname(__FILE__).'/controller.php');

// include the app
require_once( dirname(__FILE__).'/app.php' );