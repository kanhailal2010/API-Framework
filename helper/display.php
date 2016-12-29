<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class Display extends helper
{
	private $debug = 0;

	public $err;

	function __construct()
	{
		$this->obj = instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Display the response success code and response
	 *
	 *
	 * @access	public
	* @param	(array) response
	* @param 	(string) Response object name
	* @return 	nothing
	 */
	public function success($response,$responseObjectName='response')
	{
		//set the success status
		$this->_set_success($response,$responseObjectName);

		//display the error
		$this->obj->format_response($this->obj->response);
	}

	/**
	 * Display the response error code and error message
	 *
	 *
	 * @access	public
	 * @param	error_code
	 */
	function error($error_code,$extraMessage='')
	{
		//set the error
		$this->_set_error($error_code,$extraMessage);

		//display the error
		$this->obj->format_response($this->obj->response);
	}

	/**
	 * Display the response error code and error message
	 * Meant for internal errors
	 *
	 * @access	public
	 * @param	error_code
	 */
	public function internal_error($error_code)
	{
		// report error to dev
		$this->report_to_dev($error_code);

		$this->obj->response->status = false;
		$this->obj->response->status_code = $error_code;
		$this->obj->response->error 		= $this->_internal_errors($error_code);

		//display the error
		$this->obj->format_response($this->obj->response);
	}

	/**
	* Set the response error code and error message
	*
	* @access	private
	* @param	(array) response
	* @param 	(string) Response object name
	* @return 	nothing
	*/
	private function _set_success($response,$responseObjectName='response')
	{
		$this->obj->response->status = true;
		$this->obj->response->{$responseObjectName} = $response;
	}

	/**
	 * Set the response error code and error message
	 *
	 *
	 * @access	private
	 * @param	error_code
	 */
	private function _set_error($error_code,$extraMessage='')
	{
		$this->obj->response->status = false;
		$this->obj->response->status_code = $error_code;
		$this->obj->response->error 		= $this->_errors($error_code).$extraMessage;
	}

	/**
	* return the errors list
	*
	*/
	public function errors()
	{
		echo json_encode($this->_errors());
	}

	/**
	 * error code and error message
	 *
	 * error codes ending with the following 
	 * 0 	=> authentication
	 * 1 	=> Invalid
	 * 2 	=> Failed
	 * 3 	=> Not Found
	 * 4 	=> No Records
	 * 5 	=> Missing value
	 *
	 * @access	private
	 * @param	error_code
	 */
	private function _errors($code='')
	{
		$error_array = array(
			// url related error
			'404'	=> 'Requested URL does not exist',
			'1000'	=> 'This feature is not available right now',
			// Authorization error
			'1001'	=> 'Authorization failed. Please check your credentials.',
			// token related error
			'1002' 	=> 'Token Missing',
			'1003'	=> 'Invalid Token',
			'1004'	=> 'No Records',
			'1005' 	=> 'Required fields missing',
			'1006'	=> 'Failed to create token',
			'1007' 	=> 'You are not authorized for this action',
			'1008'	=> 'Nothing to update',
			'1009'	=> 'Date Range is greater than 2 Months',
			'1010'  => 'Sorry !! Not Allowed',
			// Api related error
			'1050'	=> 'Please respect API call limits, your request limits are crossed.',
			'1051'	=> 'Call duration limit crossed. Your token has expired',
			);
		if(!empty($code))
			return $error_array[$code];
		else
			return $error_array;
	}

	/**
	 * Error codes for internal error reporting
	 * For keeping track of all the internal errors
	 * starting with 1 for creative
	 * BY default it will show INTERNAL ERROR with error_code
	 * @return [string] [error message]
	 */
	function _internal_errors($error_code)
	{
		$internal_errors = array(
			111111 => 'Internal errors 1',
			222222 => 'Internal errors 2',
			333333 => 'Internal errors 3',
			444444 => 'Internal errors 4',
			555555 => 'Internal errors 5',
			666666 => 'Internal errors 6'
			);
		if($error_code===SECRET_CODE) {
			return $internal_errors;
		}
		else {
			return 'Internal Error';
		}
	}

	// send error reports to developer
	function report_to_dev($error_code)
	{

		$this->obj->loadHelper('phpmailer/phpmailer','mail');
		define('EMAIL','email@website.com');
		define('SUPPORT_EMAIL','support@website.com');
		define('PASSWORD','password');
		define('PORT',465); // 465 // 587
		define('ENCRYPTION','ssl');
		define('SERVER','in.mailjet.com');

		if($this->obj->debug)
		{
			echo "email =>>".EMAIL."<br/>";
			echo "password =>>".PASSWORD."<br/>" ;
            echo "PORT =>>".PORT."<br/>";
			echo "SERVER =>>".SERVER."<br/>";
			echo "Encryption =>>".ENCRYPTION."<br/>";
			echo "support email =>>".SUPPORT_EMAIL."<br/><br/><br/>";
    }


		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		//date_default_timezone_set('Etc/UTC');

		//Tell PHPMailer to use SMTP
		$this->obj->mail->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->obj->mail->SMTPDebug = 0;
		if($this->obj->debug) {
			$this->obj->mail->SMTPDebug = 2;
		}

		//Ask for HTML-friendly debug output
		$this->obj->mail->Debugoutput = 'html';

		//Set the hostname of the mail server
		$this->obj->mail->Host = SERVER;

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		// Or Set 465 for authenticated ssl
		$this->obj->mail->Port = PORT;

		//Set the encryption system to use - ssl (deprecated) or tls
    if(ENCRYPTION!=NONE) {
			$this->obj->mail->SMTPSecure = ENCRYPTION;
    }

		//Whether to use SMTP authentication
		$this->obj->mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$this->obj->mail->Username = EMAIL;

		//Password to use for SMTP authentication
		$this->obj->mail->Password = PASSWORD;

		//Set who the message is to be sent from
		$this->obj->mail->setFrom(SUPPORT_EMAIL, 'Support');

		//Set an alternative reply-to address
		//$this->obj->mail->addReplyTo(SUPPORT_EMAIL, 'Reply me');

        //Set who the message is to be sent to
        $this->obj->mail->addAddress('email@website.com','Name');

		//Set the subject line
		$this->obj->mail->Subject = 'Client Internal Error for apiToken ['.$this->obj->tokenData['api_token'].'] and user_id ['.$this->obj->tokenData['api_user_id'].'] ';

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->obj->mail->msgHTML("
			Internal Error Details  <pre>".
			"<br/> The Error Code : ".$error_code.
			"<br/> The Token : ".print_r($this->obj->tokenData,true).
			"<br/> The Response : ".print_r($this->obj->response,true).
			"<br/> The Request : ".print_r($_REQUEST,true).
			"<br/> The Request Method : ".requestMethod().
			"</pre>"
			);

		//Replace the plain text body with one created manually
		$this->obj->mail->AltBody = 'This is a plain-text message body';

		if(!$this->obj->mail->send())
		{
			if($this->obj->debug)
				echo "could not send error mail to dev";
		}
		else
		{
			if($this->obj->debug)
				echo "sent mail successfully";
		}
	}
}