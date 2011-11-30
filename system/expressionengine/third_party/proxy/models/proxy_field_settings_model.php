<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 *
 * @author Bryant Hughes
 * @version 0.1
 * @copyright The Good Lab - http://thegoodlab.com , 18 August, 2011
 **/

/**
 * Model for the Proxy_channel_field_settings table
 **/

class Proxy_field_settings_model extends CI_Model {
  
	public $site_id;
	
	var $_ee;
	var $cache;
		
	function __construct()
	{
		parent::__construct();
		
		$this->_ee =& get_instance();
		$this->site_id = $this->_ee->config->item('site_id');
		
		//prep-cache
		if (! isset($this->_ee->session->cache['proxy_channel_fields_settings']))
		{
			$this->_ee->session->cache['proxy_channel_fields_settings'] = array();
		}
		$this->cache =& $this->_ee->session->cache['proxy_channel_fields_settings'];
	
  }

  function get_field_settings_by_field_name(){
  	
  	if(isset($this->cache['all_channel_fields']))
		{
			return $this->cache['all_channel_fields'];
		}
		
		else
		{
			$query = $this->db->query("SELECT fs.site_id, fs.channel_id, fs.field_id, fs.override_substitution_type, fs.substitution_type, fs.substitution_method, fs.placeholders, fs.placeholder_index, cf.field_name, cf.field_type
	    FROM exp_proxy_channel_field_settings fs
	    JOIN exp_channel_fields cf ON cf.field_id = fs.field_id 
	    WHERE fs.site_id = ". $this->site_id);

	    $fields = false;

	    if($query->num_rows() > 0){

	      $fields = array();

	      foreach ($query->result_array() as $row){
	        $fields[$row['field_name']] = $row;
	      }

	    }
	
		$this->cache['all_channel_fields'] = $fields;
	    return $this->cache['all_channel_fields'];
		}

  }

	/**
	 * Returns all channel field settings
	 *
	 * @return void
	 * @author Bryant Hughes
	 */
	function get_settings()
	{

		if(isset($this->cache['channel_field_settings']))
		{
			return $this->cache['channel_field_settings'];
		}
		
		else
		{
			$query = $this->db->query("SELECT * 
	    FROM exp_proxy_channel_field_settings fs
	    JOIN exp_channel_fields cf ON cf.field_id = fs.field_id 
	    WHERE fs.site_id = ". $this->site_id);

	    $channel_settings = false;

	    if($query->num_rows() > 0){

	      $channel_settings = array();

	      foreach ($query->result_array() as $row){
	        if(isset($channel_settings[$row['channel_id']])){
	          $channel_settings[$row['channel_id']][$row['field_id']] = $row;
	        }else{
	          $channel_settings[$row['channel_id']] = array();
	          $channel_settings[$row['channel_id']][$row['field_id']] = $row;
	        }
	      }

	    }
	
		$this->cache['channel_field_settings'] = $channel_settings;
	    return $this->cache['channel_field_settings'];
		}

	}
	
	/**
	 * Returns a specific field, from a specific channel's settings
	 *
	 * @param string $channel_id 
	 * @param string $field_id 
	 * @return array - of field settings
	 * @author Bryant Hughes
	 */
	function get_channel_field_setting($channel_id, $field_id)
	{

		$channel_settings_data = $this->get_settings();

		if(isset($channel_settings_data[$channel_id][$field_id]))
		{
			return $channel_settings_data[$channel_id][$field_id];
		}
		else
		{
			return FALSE;
		}

	}
		
	function insert_new_field_settings()
	{
		
		$error = false;
		
		$fields = array();
		
		foreach($_POST as $field_name => $field_data){
					
			if($field_name == "Submit") continue;
			
			list($throw_away, $channel_id, $field_id) = explode('_', $field_name);
			$override_substitution = $field_data['substitution_type'] == "global" ? 'n' : 'y';
			
			$fields[] = array(
				'site_id' => $this->site_id
				,'channel_id' => $channel_id
				,'field_id' => $field_id
				,'override_substitution_type' => $override_substitution
				,'substitution_type' => $field_data['substitution_type']
				,'substitution_method' => $field_data['substitution_method']
				,'placeholders' => addslashes($field_data['placeholders'])
				,'placeholder_index' => $field_data['placeholder_index']
				,'placeholder_type' => $field_data['placeholder_type']
			);
			
		}
		
		// clense current settings out of DB : we add the WHERE site_id = $site_id, because the only setting we want to save is the module_id 
		// setting, which is set to site_id 0 -- because its not site specific
		$sql = "DELETE FROM exp_proxy_channel_field_settings WHERE site_id = $this->site_id";
		if( ! $this->db->query($sql)){
			return FALSE;
		}
		
		if(! $this->db->insert_batch('exp_proxy_channel_field_settings', $fields)){
			return FALSE;
		}else{
			return TRUE;
		}
			
	}
		
}
	
	
	
	
	
	
	
	