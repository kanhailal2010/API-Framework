<?php

define('PROFILING_SPEED', 0.05);

// Whitelist IP Address
define('WHITELIST_IP','127.0.0.1,170.75.146.226,208.77.22.83,192.168.5.4,192.168.81.16,119.81.167.236');

// Secret code to display internal errors
define("SECRET_CODE", 'your_secret_code');

// folder
define('CONTROLLER_FOLDER','controller');

// Api
define('CALL_DURATION_LIMIT',86400); // one day
define('CALL_LIMIT',10000);
define('ACCESS_TOKEN_HASH','kpi_access_token');

//encryption
define('ENCRYPTION_KEY','random_text');

// base url
define('BASE_URL',$config->base_url);
define('DOMAIN',$domainName);
define('LOCALHOST','http://localhost/');


// DB listing limit
define('LISTING_LIMIT', 200);
define('DATE_RANGE_LIMIT',8640000); // 100 days range , (5356800)62 days range

// Table Constant
define('TABLE_USER','users');


// Filename of log to use when none is given to write_log
define("LOG_DIRECTORY", getcwd()."/logs/");//  '/tmp/openapi_logs/');//getcwd()."/logs/");
define("LOG_DEFAULT_FILE","default.log");//"/afs/ir/your-home-directory/logs/default.log");



