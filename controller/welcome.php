<?php

class welcome extends Bidstalk {

	public $debug = 0;

	/**
	* the index
	* @method GET
	*/
	public function get_index()
	{
		$this->_common();
	}

	/**
	* the index
	* @method POST
	*/
	public function post_index()
	{
		$this->_common();
	}

	/**
	* the index
	* @method PUT
	*/
	public function put_index()
	{
		$this->_common();
	}

	private function _common()
	{
		$data = [];
		return $data;
	}
}