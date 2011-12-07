<?php
class Proxy_upd
{
	public $version = '1.0';
	
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->site_id = $this->EE->config->item('site_id');
	}
	
	public function install()
	{
		$this->EE->db->insert('modules', array(
			'module_name' => 'Proxy',
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		));
		
		$this->EE->load->dbforge();
		
		//create proxy module settings table
		$fields = array(
			'id'		=>	array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'null' => FALSE, 'auto_increment' => TRUE),
			'site_id'	=>	array('type' => 'int', 'constraint' => '8', 'unsigned' => TRUE, 'null' => FALSE, 'default' => '1'),
			'var'		=>	array('type' => 'varchar', 'constraint' => '60', 'null' => FALSE),
			'var_value'	=>	array('type' => 'varchar', 'constraint' => '100', 'null' => FALSE)
		);
		
		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('proxy_settings');
		
		// get the module id
		$results = $this->EE->db->query("SELECT * FROM exp_modules WHERE module_name = 'Proxy'");
		$module_id = $results->row('module_id');
			
		$sql = array();
		$sql[] = 
					"INSERT IGNORE INTO exp_proxy_settings ".
					"(id, site_id, var, var_value) VALUES ".
					"('', '0', 'module_id', " . $module_id . ")";

    $sql[] = 
          "INSERT IGNORE INTO exp_proxy_settings ".
          "(id, site_id, var, var_value) VALUES ".
          "('', " . $this->site_id . ", 'global_substitution_enabled', 'n')";

    $sql[] = 
          "INSERT IGNORE INTO exp_proxy_settings ".
          "(id, site_id, var, var_value) VALUES ".
          "('', " . $this->site_id . ", 'global_substitution_type', 'all')";
		
		//insert global settings
		foreach ($sql as $query){
			$this->EE->db->query($query);
		}
		
		//TODO : create proxy channel settings table
		
		//create proxy channel fields settings table
		$fields = array(
			'id'		=>	array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'null' => FALSE, 'auto_increment' => TRUE),
			'site_id'	=>	array('type' => 'int', 'constraint' => '8', 'unsigned' => TRUE, 'null' => FALSE, 'default' => '1'),
			'channel_id' => array('type' => 'int', 'constraint' => '8', 'unsigned' => TRUE, 'null' => FALSE),
			'field_id' => array('type' => 'int', 'constraint' => '8', 'unsigned' => TRUE, 'null' => FALSE),
			'override_substitution_type' => array('type' => 'varchar', 'constraint' => '2', 'null' => FALSE),
			'substitution_type' => array('type' => 'varchar', 'constraint' => '20', 'null' => FALSE),
			'substitution_method' => array('type' => 'varchar', 'constraint' => '20', 'null' => FALSE),
			'placeholders' => array('type' => 'varchar', 'constraint' => '9999', 'null' => FALSE),
			'placeholder_index' => array('type' => 'int', 'constraint' => '8', 'unsigned' => TRUE, 'null' => TRUE),
			'placeholder_type' => array('type' => 'varchar', 'constraint' => '20', 'null' => FALSE),
			'number_of_loops' => array('type' => 'int', 'constraint' => '5', 'unsigned' => TRUE, 'null' => TRUE)
		);
		
		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('proxy_channel_field_settings');
		
		return TRUE;
	}
	
	public function update( $current = '' )
	{
		if($current == $this->version) { return FALSE; }
		return TRUE;
	}
	
	public function uninstall()
	{
	  
		$this->EE->load->dbforge();
		
		$this->EE->db->query("DELETE FROM exp_modules WHERE module_name = 'Proxy'");
		
		$this->EE->dbforge->drop_table('proxy_settings');
		$this->EE->dbforge->drop_table('proxy_channel_field_settings');
		
		return TRUE;
	}
}

/* End of File: upd.module.php */