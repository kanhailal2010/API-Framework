<?php

// for adding autoload sys files
$autoload = new stdClass();
$autoload->sys = new stdClass();

// autoload config
$autoload->sys->config = $config;

//initialize database
$db = mysqli_connect($config->db_host,$config->db_username,$config->db_password,$config->db_name,$config->db_port);
$autoload->sys->db = $db;

// initialize encrypt class
$encrypt = new encrypt();
$autoload->sys->encrypt = $encrypt;

//initialize infobright database
$idb = new MysqliDb($config->ib_db_host,$config->ib_db_username,$config->ib_db_password,$config->ib_db_name,$config->ib_db_port);
$autoload->sys->idb = $idb;

// initialize uri
$uri = new Uri();
$autoload->sys->uri = $uri;

// initialize input
$input = new Input();
$autoload->sys->input = $input;
