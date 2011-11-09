<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 *
 * @author Bryant Hughes
 * @version 0.1
 * @copyright The Good Lab - http://thegoodlab.com, 18 August, 2011
 **/

/**
 * Model for the Proxy_settings table
 **/


class Proxy_settings_model extends CI_Model {
  
	public $site_id;
	
	var $_ee;
	var $cache;
		
	function __construct()
	{
		parent::__construct();
		
		$this->_ee =& get_instance();
		$this->site_id = $this->_ee->config->item('site_id');
		
		//prep-cache
		if (! isset($this->_ee->session->cache['proxy_settings']))
		{
			$this->_ee->session->cache['proxy_settings'] = array();
		}
		$this->cache =& $this->_ee->session->cache['proxy_settings'];
	
  }
	
	
	/**
	 * Get proxy settings from cache or db
	 *
	 * @return array - of proxy settings
	 * @author Bryant Hughes
	 */
	function get_proxy_settings(){
	  
	  if(isset($this->cache['proxy_settings']))
		{
			return $this->cache['proxy_settings'];
		}
		
		else
		{	
			$query = $this->db->query("SELECT *
	    FROM exp_proxy_settings
	    WHERE site_id = ". $this->site_id);

	    $settings = false;

	    if($query->num_rows() > 0)
			{

	      $settings = array();

	      foreach ($query->result_array() as $row){
	        $settings[$row['var']] = $row['var_value'];
	      }
	
				$this->cache['proxy_settings'] = $settings;

	    }
			
	    return $settings;
		}
	  
	}
	
	/**
	 * Loops through all settings that are found in the POST header and then deletes/replaces
	 * old settings with the new ones.
	 *
	 * @return boolean - true/false depending on the ability to update the db
	 * @author Bryant Hughes
	 */
	function insert_new_settings()
	{
		
		$success = true;
		
		// get current settings out of DB
		$sql = "SELECT * FROM exp_proxy_settings WHERE site_id = $this->site_id";
		$settings_result = $this->db->query($sql);
		
		$old_settings = $settings_result->result_array();
				
		$current_settings = array();
				
		foreach ($old_settings as $csetting)
		{
			$current_settings[$csetting['var']] = $csetting['var_value'];
		}
			
		// clense current settings out of DB : we add the WHERE site_id = $site_id, because the only setting we want to save is the module_id 
		// setting, which is set to site_id 0 -- because its not site specific
		$sql = "DELETE FROM exp_proxy_settings WHERE site_id = $this->site_id";
		$this->db->query($sql);
				
		// insert settings into DB
		foreach ($_POST as $key => $value)
		{
			if ($key !== 'submit' && $key !== 'Submit')
			{
        // $key = $DB->escape_str($key);
        if(!$this->db->query($this->db->insert_string(
         "exp_proxy_settings", 
         array(
           'var'       => $key,
           'var_value' => $value, 
           'site_id'   => $this->site_id
         )
        ))){
          $success = false;
        }
			}
		}
		
		return $success;
	
	}
		
}
	
	
	
	
	
	
	
