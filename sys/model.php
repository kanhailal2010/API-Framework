<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class model
{
	public $db;
	public $idb;

	function __construct()
	{
		global $db;
		$this->db =  $db;
		global $idb;
		$this->idb = $idb;
	}
}