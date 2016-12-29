<?php

// common functions

	/**
	* determine the request method and run controller methods accordingly
	*
	* @access public
	*/
	function requestMethod()
	{
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		return $request_method;
	}

	/**
	* create method name by request type
	*
	* @access public
	* @param  method_name 
	* @return modified method name based on request type, else returns index, else returns the default controller
	*/
	function restful($modelName='index')
	{
		if($modelName!='')
			return requestMethod().'_'.$modelName;
		else
			return $config->default_method;
	}


	/**
	 * [date_range to prevent form querying large date range]
	 * @param  [pipe seperated date range] $dateRange      [the queried date range in the format dateFrom|dateTo ex: 2014-01-01|2014-02-02  ]
	 * @param  [string] $durationString [enter the allowed range, ex: 2 months ]
	 * @return [bool]                 [returns true if range is within allowed range, else false ]
	 */
	function is_date_range_optimized($dateRange='',$durationString='+62 Days')
	{
		$dateRange = explode("|", $dateRange);

		$startDate = strtotime($dateRange[0]);
		$endDate 	= strtotime($dateRange[1]);
		$diff 		= ($endDate-$startDate);


/*		echo $startDate." Start Date $dateRange[0] <br/>";
		echo $endDate." End Date $dateRange[1]<br/>";
		echo $diff." Diff Date <br/>";*/

		if($diff>DATE_RANGE_LIMIT) return false;
		else
			return true;
		
	/*	$today = strtotime("now");
		$afterDuration = strtotime($durationString);
		$difference = ($afterDuration-$today);

		echo $today ." Todays Time <br/>";
		echo $afterDuration ." After $durationString <br/>";
		echo $difference ." Difference of $durationString <br/>";*/
	}


	/**
	* create instance of the main controller file (Bidstalk)
	*
	* @access public
	* @return instance of the main controller file
	*/
	function &instance()
	{
		$bidstalk = Bidstalk::get_instance();
		return $bidstalk;//->autoload['sys'];
	}

	function dbug()
	{
		static $doc_root;
		$output = '';
		$args = func_get_args();

		// do not repeat the obvious (matter of taste)
		if (!isset($doc_root)) {
			$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
		}

		$backtrace = debug_backtrace();
		// you may want not to htmlspecialchars here
		$line = htmlspecialchars($backtrace[0]['line']);
		$file = htmlspecialchars(str_replace(array('\\', $doc_root), array('/', ''), $backtrace[0]['file']));
		$class = !empty($backtrace[1]['class']) ? htmlspecialchars($backtrace[1]['class']) . '::' : '';
		$function = !empty($backtrace[1]['function']) ? htmlspecialchars($backtrace[1]['function']) . '() ' : '';
		$output .= "<hr/> Debug &raquo;&raquo; ($file) $class $function LINE ($line) <pre> ==<<<<<<<<<< ";
		$i = 0;
		foreach ($args as $arg) {
			if($i==0) $output .= "<strong> ".print_r($arg,true)." </strong>";
			else
			$output .= print_r($arg,true);
			$i++;
		}
		$output .=" >>>>>>>>>>== </pre>";
		echo $output;
	}


	// get client's ip address 
	function get_client_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

/**
  * write_log($response[, $logfile])
  *
  * Author(s): thanosb, ddonahue
  * Date: May 11, 2008
  * 
  * Writes the values of certain variables along with a message in a log file.
  *
  * Parameters:
  *  $message:   Message to be logged
  *  $logfile:   Path of log file to write to.  Optional.  Default is LOG_DEFAULT_FILE.
  *
  * Returns array:
  *  $result[status]:   True on success, false on failure
  *  $result[message]:  Error message
  */
 
function write_log($response, $logfile='') {
  // Determine log file
  if($logfile == '') {
    // checking if the constant for the log file is defined
    if (defined(LOG_DEFAULT_FILE) == TRUE) {
        $logfile = LOG_DEFAULT_FILE;
    }
    // the constant is not defined and there is no log file given as input
    else {
        error_log('No log file defined!',0);
        return array(status => false, message => 'No log file defined!');
    }
  }
 
  // Get time of request
  if( ($time = $_SERVER['REQUEST_TIME']) == '') {
    $time = time();
  }
 
  // Get IP address
  //if( ($remote_ip = $_SERVER['REMOTE_ADDR']) == '') {
    $remote_ip = get_client_ip();//"REMOTE_ADDR_UNKNOWN";
  //}
 
  // Get requested script
  if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
    $request_uri = "REQUEST_URI_UNKNOWN";
  }

  // Get Request method
  if( requestMethod() ) $request_method =  requestMethod();
 
  // Format the date and time
  $date = date("Y-m-d H:i:s", $time);
 
 // log by date
 $logfile = date('Y-m-d_').$logfile;
 
 $logfile = LOG_DIRECTORY.$logfile;

 // log all the fields
 $fields = '';
 foreach ($response['fields'] as $key => $value) {
 	$fields .= "[".$key.":".$value."]";
 }
 // populate the content for logging
 if(is_array($response))
 $content = array($date, $response['user_id'], $remote_ip, $request_method,  $request_uri, $fields ,$response['content'] );
 else
 $content = array($date, $remote_ip, $request_method, $request_uri, $response );

  // Append to the log file
  if($fd = @fopen($logfile, "a")) {
    $result = fputcsv($fd, $content);
    fclose($fd);
 
    if($result > 0)
      return array('status' => true);  
    else
    {
		error_log('Unable to write to '.$logfile.'!',0);
      return array('status' => false, 'message' => 'Unable to write to '.$logfile.'!');
    }
  }
  else {
		error_log('Unable to open log '.$logfile.'!',0);
    return array('status' => false, 'message' => 'Unable to open log '.$logfile.'!');
  }
}


function profiler($profile_start,$class,$method,$comment="")
{

    $profile_end = microtime(true);
    $elapsed = ($profile_end - $profile_start);
    $speed = (PROFILING_SPEED>PROFILE_TIME) ? PROFILING_SPEED : PROFILE_TIME;


    if(PROFILE_TIME < $elapsed || PROFILING_SPEED)
    {
        $file_name = 'profiler-'.$speed.'-'.date("Y-m-d").'.log';

        //use your own path
        $file_path = LOG_DIRECTORY;//.'/logs/';
        $complete_path = $file_path.$file_name;

        $profilerInfo = array(
        	'key' 	=> 'OPENAPI',
            'date' => date('YmdHis'),
            'startTime' => $profile_start,
            'endTime' => $profile_end,
            'elapsed' => $elapsed,
            'class' => $class,
            'method' => $method,
            'comment' => $comment
        );

	$profilerInfo = implode('|',$profilerInfo);
	$message = $profilerInfo."\n";

	if (file_exists($complete_path)) {
	    $fh = fopen($complete_path, 'a');
	    fwrite($fh, $message);
	} else {
	    $fh = fopen($complete_path, 'w');
	    fwrite($fh, 'date|startTime|endTime|elapsed|class|method|comment'."\n");
	    $fh = fopen($complete_path, 'a');
	    fwrite($fh, $message);
	}
	fclose($fh);
	}
	return $profile_end;
}

function queryLog($queryLog_start,$class,$method,$token,$query="")
{

    $queryLog_end = microtime(true);
    $elapsed = ($queryLog_end - $queryLog_start);

    if(QUERYLOG && QUERYLOG_TIME < $elapsed)
    {
        $file_name = 'openapi_query.log';
        //use your own path
        $file_path = LOG_DIRECTORY;
        $complete_path = $file_path.$file_name;

        $queryLogInfo = array(
            'date'      => date('YmdHis'),
            'startTime' => $queryLog_start,
            'endTime'   => $queryLog_end,
            'elapsed'   => $elapsed,
            'class'     => $class,
            'method'    => $method,
            'query'   => $query,
            'token' 	=> $token
        );

        
        $queryLogInfo = implode('|',$queryLogInfo);
        $message = $queryLogInfo."\n";

        if (file_exists($complete_path)) {
            $fh = fopen($complete_path, 'a');
            fwrite($fh, $message);
        } else {
            $fh = fopen($complete_path, 'w');
            fwrite($fh, 'date|startTime|endTime|elapsed|class|method|user|user_id|query'."\n");
            $fh = fopen($complete_path, 'a');
            fwrite($fh, $message);
        }

        fclose($fh);
    }
	return $profile_start;
}