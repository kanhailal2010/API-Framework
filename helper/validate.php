<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class Validate extends helper
{
	private $debug = 0;

	public function __construct()
	{
		self::$obj = instance();
	}

	/**
	 * Validate if the parameter is alphanumeric
	 * Accepts underscores, hyphen and single quotes
	 * @param  [string] $alphanumeric [string to check for alphanumeric]
	 * @return [string]               [returns json error on failure]
	 */
	public static function alphanumeric($string)
	{
		if(preg_match('/^[a-z0-9\_\-\' ]+$/i', $string))
		{
			return $string;
		}
		else
		{
			self::$obj->display->error(2002,', Accepts alphanumeric,underscores,hyphen and single quote characters only');
		}
	}
}