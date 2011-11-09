<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proxy
{
	public $return_data = NULL;
	
	public function __construct()
	{
		$this->EE =& get_instance();

		
		// -------------------------------------------
		//  Prepare Cache
		// -------------------------------------------
		if (! isset($this->EE->session->cache['proxy']))
		{
			$this->EE->session->cache['proxy'] = array();
		}
		$this->cache =& $this->EE->session->cache['proxy'];

		$this->index();

	}

	public function pr($var) { print '<pre>'; print_r($var); print '</pre>'; }

	public function index()
	{
		$this->EE->load->model('proxy_sql', 'sql');

		//$this->entry_id = $this->EE->TMPL->fetch_param('entry_id');
		//$entry_info = $this->EE->sql->get_entry_info(1);
		$vars = array();
		$var_row = array(
			"proxy_channel_1_field_1" => '{channel_1_field_1}'
		);
		$vars[] = $var_row;

		//$this->pr($this->EE->TMPL->tag_data);

		//$this->return_data = $output = $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);

		//print_r($entry_info);
			
	}
}

/* End of File: mod.module.php */