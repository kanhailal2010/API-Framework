<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class Uri
{
	public $url;
	private $debug = 0;

	function __construct() {
		$url                     = (isset($_REQUEST['_url'])) ? explode( '/',trim($_REQUEST['_url'],"/")) : null;
		$this->url["controller"] = !empty($url[0]) ? $url[0] : 'welcome';
		$this->url["method"]     = !empty($url[1]) ? $url[1] : 'index';
		$this->url["params"]     = (count($url)>2) ? (array_slice($url, 2,(count($url)-2), true)) : array();

		foreach ($url as $index => $entity) {
			$this->url[++$index] = $entity;
		}

		if($this->debug) { dbug(" The this->url ==( ".print_r($this->url,true)." )== "); }
	}

	/**
	* Returns current url
	*
	*/
	function current()
	{
		return $_SERVER['REQUEST_URI'];
	}

	/**
	* create url segments
	*
	* @access public
	* @param  segment key to return segment value
	* @return segments in array format
	*/
	function segment($key='')
	{
		if($key==='')
			return $this->url;
		else
			return isset($this->url[$key]) ? $this->url[$key] : false;
	}
}