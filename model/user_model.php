<?php
class user_model extends model {

	var $select;

	function __construct()
	{
		parent::__construct();
		// fields for select query
		$this->select = array(
			'unique' 	    		=> TABLE_USER.".id user_id",
			'default' 	    		=> TABLE_USER.".id user_id ,".TABLE_USER.".user_name ,".TABLE_USER.".apikey api_key"
			);
	}

	/**
	 * Get user details
	 * @param  integer $id [The Id of the user]
	 * @return [object/boolean]      [returns result object on success else false on failure]
	 */
	function get_user($get)
	{
		//select
		$col = $this->select['default'];
		// where 
		$this->mdb->where('id',$get->id);
		//limit
		$limit = null;
		// fetch
		$result = $this->mdb->get(TABLE_USER,$limit,$col);

		//return
		if(!empty($result)) return $result;
		else 		return false;
	}

	/**
	 * Add user details to the database
	 * @param [array] $insertUserData [an array with index as the table column name]
	 */
	function add_user_details($insertUserData)
	{
		// insert new row to Table Users
		$id   = $this->cdb->insert(TABLE_USER,$insertUserData);

		if($id && $pid)
			return $id;
		else
			return false;		
	}

	/**
	 * Check if the user name is unique
	 * @param  [object]  $obj [username in object format]
	 * @return boolean      [returns true if the username is available else false]
	 */
	function is_unique_username($obj)
	{
		// select
		$col = $this->select['unique'];
		// where
		$this->mdb->where(TABLE_USER.'.user_name',$obj->userName);
		// fetch
		$result = $this->mdb->get(TABLE_USER);
		if(!empty($result))
			return false;
		else
			return true;
	}
}