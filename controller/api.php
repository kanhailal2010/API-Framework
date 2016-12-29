<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class api extends Bidstalk {

	public $debug = 0;


	/**
	* the index
	* @method GET
	*/
	public function get_index()
	{
		echo "this is api controller index";
	}


	/**
	* Display the allowed limits
	* @method GET
	*/
	public function get_limits()
	{
		$profile_start = microtime(true);

		$limitsData = array(
					'call_limit'     =>	CALL_LIMIT,
					'duration_limit' =>	CALL_DURATION_LIMIT,
					'listing_limit'  =>	LISTING_LIMIT
					);

		$this->input->do_put($this->config->base_url."/api/limit",[]);

		if(!empty($limitsData))
			$this->display->success($limitsData,'limits');
		else
			$this->display->error(1502);

		$profile_start = profiler($profile_start,__CLASS__,__FUNCTION__,'openapi');
	}

	public function put_limit() {
		$this->display->success(["kanhai" => "lal"]);
	}

	/**
	* display all errors defined for api
	*
	*/
	public function get_errors()
	{
		$profile_start = microtime(true);
		$this->display->errors();
		$profile_start = profiler($profile_start,__CLASS__,__FUNCTION__,'openapi');
	}

	public function get_internal_errors()
	{
		$profile_start = microtime(true);
		$profile_start = profiler($profile_start,__CLASS__,__FUNCTION__,'openapi');
	}
}