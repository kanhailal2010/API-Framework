<?php  if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class Bidstalk
{
	public $config;
	public $uri;
	public $autoload;
	public $controller;
	public $model;
	public $session;
	public $helper;
	public $db;
	public $debug=0;
	public $response;
	public $allowed_url;
	public $tokenData;
	public $is_internal = false;
	public $block_url_access = true;
	public $vo;

	public static $instance;

	function __construct()
	{
		self::$instance 		=& $this;

		$this->response 		=  new stdClass();
		$this->response->status = 0;
		
		// autoload base files
		$this->_autoload();
		
		//autoload helper files
		$this->loadHelper('display');

		// to start debugging
		if($this->input->get_post('abkyahua') || $this->input->put('abkyahua'))
			$this->start_debug();
		
		// start profiling
		$startProfiling = $this->input->get_post('speed_test');

		if(($startProfiling && $startProfiling>0) || PROFILING_SPEED)
			$this->start_profiling($startProfiling);
	}

	/**
	* for creating instance of the main controller file
	*
	* @access public
	* @return (object) main controller file
	*/
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	* autoload system files
	*
	*/
	private function _autoload()
	{
		global $autoload;
		foreach ($autoload->sys as $className => $objectName) {
			$this->{$className} = $objectName;
		}
	}

	/**
	* For loading  model in controller
	*
	* @access public
	* @param model class/file name
	* @return model object
	*/
	public function loadModel( $model,$model_alias='' )
	{
		$file = dirname(__FILE__).'/../model/'. $model .'.php';
		if(file_exists($file))
			require_once( $file );
		else
			exit("$model does not exist.");

		if($model_alias=='')
			$this->{$model} = new $model();
		else
			$this->{$model_alias} = new $model();
	}


	/**
	* For loading  Helper in controller
	*
	* @access public
	* @param helper class/file name
	* @return helper object
	*/
	public function loadHelper( $helper,$helper_alias='' )
	{
		$file = dirname(__FILE__).'/../helper/'. $helper .'.php';
		if(file_exists($file))
			require_once( $file );
		else
			exit("$helper does not exist.");

		// reassign file name (if folder structure is used)
		$helper = basename($helper,'.php');

		if($helper_alias=='')
			$this->{$helper} = new $helper();
		else
			$this->{$helper_alias} = new $helper();
	}

	/**
	* formating response based on user request type
	*
	* @access  public
	* @param $response to process
	* @param $type the type of response required
	* @return the encoded response based on user request type
	*/
	public function format_response($response,$type='json')
	{

		if(!isset($response->status)) exit("Response Status not set");
		if($response->status) unset($response->error);
		if(!$this->debug)	unset($response->dev);

		$this->_write_log($response);

		if($this->debug) { $type = "html"; }

		if($type=='json')
		{
			$this->sendHeaders($response->status,$type);
			exit(json_encode($response));
		}
	}

	/**
	* log time, request, method , ip
	*
	* @access private
	* @param (object) response object
	*/
	private function _write_log($response)
	{
		if(empty($response)) {
			error_log(" Ooh oo!!  No response from API ",0);
		}

		// the respose from API
		$message = json_encode($response);

		// Log all the details of request and user details
		$log = array();
		$log['fields'] = $_REQUEST;
		$log['user_id'] = $this->tokenData['api_user_id'];
		$log['content'] = $message;

		$filename = LOG_DEFAULT_FILE;
		if(!empty($this->tokenData['for_dsp']))
			$filename = "log_for_DSP_id-".$this->tokenData['for_dsp'].".log";

		write_log($log,$filename);
	}


	/**
	* send headers based on response status
	* we do not allow cross origin request
	*
	* @access 	private
	* @param 	$status (response status)
	* @param 	$type (set the header type)
	* @return 	response Header
	*/
	private function sendHeaders($status,$type)
	{
		switch($status)
		{
			case 0 : 	header("HTTP/1.0 404 sorry!! please check your request ");
						$this->response->status = 'failed';
				break;
			case 1 : 	header("HTTP/1.0 200 great!! we got the response  ");
						$this->response->status = 'success';
				break;
			default : 	header("HTTP/1.0 404 Not Found ");
				break;
		}

		switch($type)
		{
			case 'json' : header('Content-Type: application/json'); //header('Content-Type: text/html')
				break;
			case 'xml' : header('Content-Type: application/xml');
				break;
			default : header('Content-Type: text/html');
				break;
		}
	}

	/**
	* Default Response
	*
	*/
	public function set_response($status='',$status_code='',$error='',$dev='')
	{
		$this->response              = new stdClass();
		$this->response->dev         = 'Dev Comments';//!empty($dev) ? $dev : 'Dev Comments';
		$this->response->status      = 0;//!empty($status) ? $status : 0;
		$this->response->status_code = 0;//!empty($status_code) ? $status_code : 0;
		$this->response->error       = 'Oops requested Url not found.';//!empty($error) ? $error : 'Oops requested Url not found.';
	}

	/**
	* to display all the errors
	*
	* @access public
	*/
	public function start_debug()
	{
		$this->debug = 1;
		echo "<pre> <h4> displyaing Errors</h4>";
		ini_set('display_startup_errors',1);
		ini_set('display_errors',1);
		error_reporting(-1);
		echo "</pre>";
	}

	/**
	 * Start profiling the speed
	 * @param  [integer] $microtime [enter the min time a process should take before it gets logged]
	 * @return [none]            [none]
	 */

	public function start_profiling($microtime=PROFILING_SPEED)
	{
		if(!defined('PROFILE_TIME')) {
			define('PROFILE_TIME',$microtime);
		}
	}
}