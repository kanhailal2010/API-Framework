<?php

date_default_timezone_set('UTC');

$config = new stdClass();
$domainName = 'http://'.$_SERVER['HTTP_HOST'].'/';

$config->default_controller = 'welcome'; // Default controller to load
$config->default_method = 'index'; // Default model to load
$config->error_controller = 'error'; // Controller used for errors (e.g. 404, 500 etc)

// allowed uri (withour leading/trailing slash)
$config->allowed_uri = array('welcome','api','api/errors','api/limits','auth','auth/admin','creative/types','view/logs','mobile');

// UnBlock API access for selected users
$config->block_access = 1;

$env = getenv("KPI_ENVIRONMENT");

if($env == "production")
{
    error_reporting(0);
    // First Database
    $config->base_url       = $domainName.'kpi';
    $config->db_host        = 'localhost'; // Database host (e.g. localhost)
    $config->db_name        = 'kanhai_kpi'; // Database name
    $config->db_username    = 'root'; // Database username
    $config->db_password    = 'root'; // Database password
    $config->db_port        = 3306; // Database port

    // Second database
    $config->ib_db_host     = 'localhost';
    $config->ib_db_name     = 'kanhai_kpi';
    $config->ib_db_username = 'root';
    $config->ib_db_password = 'root';
    $config->ib_db_port     = 3306;
}
else // for White Labeled API
{
    error_reporting(E_ALL);
    // First Database
    $config->base_url       = $domainName;
    $config->db_host        = '111.111.111.111'; 
    $config->db_name        = 'db_name'; // Database name
    $config->db_username    = 'db_username'; // Database username
    $config->db_password    = 'db_password'; // Database password
    $config->db_port        = 3306; // Database port

    // Second Database
    $config->ib_db_host     = '10.10.10.10.';
    $config->ib_db_name     = 'database';
    $config->ib_db_username = 'username';// 'hdfsuser';
    $config->ib_db_password = 'password';// 'hdfsuser';
    $config->ib_db_port     = 3300;
}
require_once('constant.php');
