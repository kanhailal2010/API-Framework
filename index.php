<?php

/**
 *
 *
 * @package		API Framework
 * @author		Kanhai Lal
 * @link		http://kanhailal.me
 * @since		Version 1.0
 */

// inlcude main application class file
require('sys/base.php');

// initialize app
$app = new App($autoload);
$app->handle_request();