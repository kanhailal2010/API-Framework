<?php if(!defined('BASE_URL')) exit('Direct Script Access Not Allowed');

class Input
{
	private $debug = 0;
	private $params = Array();

	function __construct()
	{
		$this->_parseParams();
	}

	/**
	 * Fetch from array
	 *
	 * This is a helper function to retrieve values from global arrays
	 *
	 * @access	private
	 * @param	array
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	function _fetch_from_array(&$array, $index = '')
	{
		if ( ! isset($array[$index]))
		{
			return FALSE;
		}
		return $array[$index];
	}

	/**
	* Fetch an item from the GET array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function get($index = NULL,$default=NULL)
	{
		// Check if a field has been provided
		if ($index === NULL AND ! empty($_GET))
		{
			$get = array();

			// loop through the full _GET array
			foreach (array_keys($_GET) as $key)
			{
				$get[$key] = ($this->_fetch_from_array($_GET, $key)) ? $this->_fetch_from_array($_GET, $key) : $default ;
			}
			return $get;
		}

		return  ($this->_fetch_from_array($_GET, $index)) ? $this->_fetch_from_array($_GET, $index) : $default ; //$this->_fetch_from_array($_GET, $index);
	}

	/**
	* Fetch response using get method
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	array
	*/
	public static function do_get($url,$responseObjectName)
	{
		$response = file_get_contents($url);
		$response = json_decode($response);
		//var_dump($response->{$responseObjectName});
		if(isset($response->{$responseObjectName}))
			return $response->{$responseObjectName};
		else
		return $response->status;
	}


	// --------------------------------------------------------------------

	/**
	* Fetch an item from the POST array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function post($index = NULL,$default=NULL)
	{
		// Check if a field has been provided
		if ($index === NULL AND ! empty($_POST))
		{
			$post = array();

			// Loop through the full _POST array and return it
			foreach (array_keys($_POST) as $key)
			{
				$post[$key] = ($this->_fetch_from_array($_POST, $key)) ? $this->_fetch_from_array($_POST, $key) : $default;
			}
			return $post;
		}

		return ($this->_fetch_from_array($_POST, $index)) ? $this->_fetch_from_array($_POST, $index) : $default;
	}

	function do_post($url,$fields='')
	{
		// populate fields
		$fields_string = (is_array($fields)) ? http_build_query($fields) : $fields;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		curl_close ($ch);

		return $server_output;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from either the GET array or the POST
	*
	* @access	public
	* @param	string	The index key
	* @return	string
	*/
	function get_post($index = '',$default=null)
	{
		if ( ! isset($_POST[$index]) )
		{
			return $this->get($index,$default);
		}
		else
		{
			return $this->post($index,$default);
		}
	}



	// --------------------------------------------------------------------

	/**
	* Fetch an item from the PUT array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	public function put($index, $default = null) {
		if (isset($this->params[$index])) 
		{
			return $this->params[$index];
		} 
		else 
		{
			return $default;
		}

	}

	/**
	 * Checks if the index exists in the PUT array
	 * @param  string $key [description]
	 * @return [boolean]      [returns true if the index exist else false]
	 */
	public function put_exist($key='')
	{
		return isset($_REQUEST[$key]) ? true : false;
	}

	/**
	 *  Parse the parameters received through GET/POST/PUT/DELETE methods
	 */
	private function _parseParams() {
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == "PUT" || $method == "DELETE") 
		{
			parse_str(file_get_contents('php://input'), $this->params);
			$GLOBALS["_{$method}"] = $this->params;
			// Add these request vars into _REQUEST, mimicing default behavior, PUT/DELETE will override existing COOKIE/GET vars
			$_REQUEST = $this->params + $_REQUEST;
		} 
		else if ($method == "GET") 
		{
			$this->params = $_GET;
		} else if ($method == "POST") 
		{
			$this->params = $_POST;
		}
	}

	/**
	* function to do PUT request 
	*
	* @access public
	* @param (string) $url
	* @param (array) $fields
	* @return (string)  response
	*/
	function do_put($url, $fields)
	{
	  $fields_string = (is_array($fields)) ? http_build_query($fields) : $fields;
	  //open connection
	  $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded'
        )
    );

    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_exec($ch);

    //execute post
    $api_response = curl_exec($ch);

    curl_close($ch);

    /*
      We need to get Curl infos for the header_size and the http_code
    */
    $api_response_info = curl_getinfo($ch); //curl_getinfo($ch, CURLINFO_HTTP_CODE);
     
    /*
      Here we separate the Response Header from the Response Body
    */
    $api_response_header = trim(substr($api_response, 0, $api_response_info['header_size']));
    $api_response_body = substr($api_response, $api_response_info['header_size']);

    return $api_response;
	}
}