<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'proxy/models/proxy_sql.php';

class Proxy_mcp
{
	
	public $data = array();
	public $sql;
	public $EE;
	public $site_id;
	public $base_url;
	
	public function __construct()
	{
		
		$this->EE =& get_instance();
		$this->site_id = $this->EE->config->item('site_id');
		$this->base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=proxy';
		
		$this->sql = new Proxy_sql();
    
		// load table lib for control panel
		$this->EE->load->library('table');
		$this->EE->load->helper('form');
		
		// Set page title
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('proxy_module_name'));
		
		// load external assets
		$this->EE->cp->add_to_head("<link rel='stylesheet' href='".$this->EE->config->item('theme_folder_url') ."third_party/proxy/css/proxy.css'>");
		$this->EE->cp->load_package_css('proxy');
		$this->EE->cp->load_package_js('proxy');
				
		//theme folder url
		$theme_folder_url = $this->EE->config->item('theme_folder_url');
		if (substr($theme_folder_url, -1) != '/') $theme_folder_url .= '/';
		$theme_folder_url.'third_party/proxy/';
		$this->data['theme_folder'] = $theme_folder_url;
				
		$nav = array();
		$nav['Module Settings'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=proxy'.AMP.'method=index';
		$nav['Field Settings'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=proxy'.AMP.'method=field_settings';
		
		$this->EE->cp->set_right_nav($nav);
		
	}
	
	/**
	 * Proxy CP index page load (proxy settings)
	 *
	 * @return view
	 * @author Bryant Hughes
	 */	
	public function index()
	{
	  
	  $this->EE->load->model('proxy_settings_model');
	
		$this->data['form_action'] = AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=proxy'.AMP.'method=submit_global_settings';
		$this->data['settings'] = $this->EE->proxy_settings_model->get_proxy_settings();
				
		return $this->EE->load->view('module_settings', $this->data, TRUE);
		
	}

	public function _dump($data)
	{
		  echo "<pre>"; 
      print_r($data); 
      echo "</pre>";
	}
		
	/**
	 * CP Field settings
	 *
	 * @return view
	 * @author Bryant Hughes
	 */
	public function field_settings()
	{
	  
		$this->EE->cp->load_package_js('plugins');
		$this->EE->load->helper('proxy_form_helper');
		
		$this->EE->load->model('proxy_field_settings_model','field_settings');
	  
		$this->data['form_action'] = AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=proxy'.AMP.'method=submit_field_settings';
	  
		$this->data['channels'] = $this->sql->get_all_channels_by_id();
		$this->data['fields'] = $this->sql->get_all_fields_by_channel_id();
		$this->data['fields_settings'] = $this->EE->field_settings->get_settings();

		$this->_dump($this->data['fields_settings']);

		$view_code = '';
		return $this->EE->load->view('field_settings', $this->data, TRUE);
		
	}
	
	
	/**
	 * After proxy settings are submitted
	 *
	 * @return void - redirects user to Proxy index page
	 * @author Bryant Hughes
	 */
	public function submit_global_settings(){
	  		
		$this->EE->load->model('proxy_settings_model');
		$success = $this->EE->proxy_settings_model->insert_new_settings();
		
		if(! $success){
		  $this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('Error saving settings.'));
		}else{
		  $this->EE->session->set_flashdata('message_success', $this->EE->lang->line('Success!'));
		}
		
		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=proxy');
		
	}
	
	/**
	 * Called after channel field settings are submitted
	 *
	 * @return void
	 * @author Bryant Hughes
	 */
	public function submit_field_settings(){
	  
	  $this->EE->load->model('proxy_field_settings_model','field_settings');
	
		$success = $this->EE->field_settings->insert_new_field_settings();
		
		if(! $success){
		  $this->EE->session->set_flashdata('message_failure', $this->EE->lang->line('Error saving settings.'));
		}else{
		  $this->EE->session->set_flashdata('message_success', $this->EE->lang->line('Success!'));
		}
		
		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=proxy'.AMP.'method=field_settings');
		  	  
	}
	
}

/* End of File: mcp.module.php */