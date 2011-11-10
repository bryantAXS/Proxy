<?php
	
	/**
	 * 
	 *
	 * @author Bryant Hughes
	 * @version 0.1
	 * @copyright The Good Lab - http://thegoodlab.com, 18 August, 2011
	 **/

	/**
	 * Model that handles all misc. queries for Proxy
	 **/
	
	class Proxy_Sql{
		
		function __construct()
		{
			$this->EE =& get_instance();
			$this->site_id = $this->EE->config->item('site_id');
		}
		
		/**
		 * returns an array of all fields in EE keyed by the channel name
		 *
		 * @return void
		 * @author Bryant Hughes
		 */
		public function get_all_channel_fields_by_channel()
		{

		  //get all channel names and ids
	    $query = $this->EE->db->query("SELECT cf.field_name, cf.field_label, c.channel_name, c.channel_title, cf.field_type, c.channel_id, cf.field_id
	    FROM exp_channel_fields as cf
	    JOIN exp_channels c ON c.field_group = cf.group_id
	    WHERE cf.site_id = ". $this->site_id ." AND c.site_id = ". $this->site_id);

	    $channels = false;

	    if($query->num_rows() > 0){

	      $channels = array();

	      foreach ($query->result_array() as $row){
	        if(isset($channels[$row['channel_name']])){
	          $channels[$row['channel_name']][$row['field_name']] = $row;
	        }else{
	          $channels[$row['channel_name']] = array();
	          $channels[$row['channel_name']][$row['field_name']] = $row;
	        }
	      }

	    }

	    return $channels;

		}
		
		public function get_all_channels_by_id()
		{
			//get all channel names and ids
	    $query = $this->EE->db->query("SELECT c.channel_name, c.channel_title, c.channel_id
	    FROM exp_channels c
	    WHERE c.site_id = ". $this->site_id);

	    $channels = false;

	    if($query->num_rows() > 0){

	      $channels = array();

	      foreach ($query->result_array() as $row){
					$channels[$row['channel_id']] = $row;
	      }

	    }

	    return $channels;
		}
		
		public function get_all_fields_by_channel_id()
		{
			
			//get all channel names and ids
	    $query = $this->EE->db->query("SELECT cf.field_name, cf.field_label, c.channel_name, c.channel_title, cf.field_type, c.channel_id, cf.field_id
	    FROM exp_channel_fields as cf
	    JOIN exp_channels c ON c.field_group = cf.group_id
	    WHERE cf.site_id = ". $this->site_id ." AND c.site_id = ". $this->site_id);

	    $fields = false;

	    if($query->num_rows() > 0){

	      $fields = array();

	      foreach ($query->result_array() as $row){
	        if(isset($fields[$row['channel_id']])){
	          $fields[$row['channel_id']][$row['field_id']] = $row;
	        }else{
	          $fields[$row['channel_id']] = array();
	          $fields[$row['channel_id']][$row['field_id']] = $row;
	        }
	      }

	    }

	    return $fields;
			
		}

		public function get_entry_info($entry_id)
		{
			
			$query = $this->EE->db->query("SELECT cd.site_id, cd.entry_id, cd.channel_id, cf.field_id
			FROM exp_channel_data cd
			JOIN exp_channels c on cd.channel_id = c.channel_id
			JOIN exp_channel_fields cf on c.field_group = cf.field_id
			WHERE cd.site_id = 1
			AND cd.entry_id = ".$entry_id.";");

			if($query->num_rows() == 1){
				return  $query->row_array();
			}else{
				return FALSE;
			}

		}

		public function matrix_field_has_data($entry_id, $field_id)
		{
			
			$query = $this->EE->db->query("SELECT * from exp_matrix_data md
WHERE md.`entry_id` = ".$entry_id." AND md.`field_id` = ".$field_id.";");

			if($query->num_rows() > 0){
				return TRUE;
			}else{
				return FALSE;
			}

		}
		
	}
	
	
	
	
	
	