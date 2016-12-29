<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class Manager extends helper
{
	private $debug = 0;

	public $err;

	function __construct()
	{
		$this->err = instance();
	}

	/**
	 * [date_range to prevent form querying large date range]
	 * @param  [pipe seperated date range] $dateRange      [the queried date range in the format dateFrom|dateTo ex: 2014-01-01|2014-02-02  ]
	 * @param  [string] $durationString [enter the allowed range, ex: 2 months ]
	 * @return [bool]                 [returns true if range is within allowed range, else false ]
	 */
	public static function date_range($dateRange,$durationString)
	{
				$today = date("Y-m-d");
		echo $today."<br/>";
		$today = date("Y-m-d",strtotime("+1 month"));
		echo $today."<br/>";
		echo "1 month ".strtotime("+1 month");
		
		$dateRange = explode('|', $dateRange);

		$strStartDate = strtotime($dateRange[0]);
		$strFromDate  = strtotime($dateRange[1]);

		$endDate = date('Y-m-d H:i:s',$strStartDate);
		$dbTokenTime = date("Y-m-d", mktime(23, 59, 59, date('m',strtotime($dt)), date('d',strtotime($dt)), date('Y',strtotime($dt)) ));

	}

}