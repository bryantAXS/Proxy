<?php

require_once PATH_THIRD .'proxy/mcp.proxy.php';
// require_once PATH_THIRD .'proxy/tests/mocks/mock.proxy_sql_model.php';
//require_once PATH_THIRD .'proxy/tests/mocks/mock.proxy_settings_model.php';

class Test_proxy_mcp extends Testee_unit_test_case{
	
	
	/* --------------------------------------------------------------
	 * PUBLIC METHODS
	 * ------------------------------------------------------------ */
		
	/**
	 * Runs before each test.
	 *
	 * @access	public
	 * @return	void
	 */
	public function setUp()
	{
		//parent::setUp();
    
		//generate mock settings model
    // Mock::generate('Mock_proxy_settings_model', get_class($this) .'_mock_model');
    // $this->_ee->proxy_settings_model = $this->_get_mock('model');
    // $this->_settings_model   = $this->_ee->proxy_settings_model;
		
		$this->_subject = new Proxy_mcp();	
	}
	
	/* --------------------------------------------------------------
	 * HELPER METHODS
	 * ------------------------------------------------------------ */
	
	/* --------------------------------------------------------------
	 * TEST METHODS
	 * ------------------------------------------------------------ */

	public function test_load_proxy_settings_view()
	{
		
		//$this->_subject->index();
		
		//$this->assertTrue(isset($this->_subject->data['form_action']));
		//$this->assertTrue(isset($this->_subject->data['settings']['global_substitution_enabled']));
		//$this->assertTrue(isset($this->_subject->data['settings']['global_substitution_type']));
		
	}
	
	public function test_load_proxy_field_settings_view()
	{
		
		$channel_fields_by_channel = array(
			'channel_1' => array(
				'channel_1_field_1' => array(
					'field_name' => 'channel_1_field_1'
					,'field_label' => 'Channel 1 field 1'
					,'channel_name' => 'channel_1'
					,'channel_title' => 'Channel 1'
					,'field_type' => 'text'
					,'channel_id' => 1
					,'field_id' => 1
				)
				,'channel_1_field_2' => array(
					'field_name' => 'channel_1_field_2'
					,'field_label' => 'Channel 1 field 2'
					,'channel_name' => 'channel_1'
					,'channel_title' => 'Channel 1'
					,'field_type' => 'text'
					,'channel_id' => 1
					,'field_id' => 2
				)
			)
			,'channel_2' => array(
				'channel_2_field_1' => array(
					'field_name' => 'channel_2_field_1'
					,'field_label' => 'Channel 2 field 1'
					,'channel_name' => 'channel_2'
					,'channel_title' => 'Channel 2'
					,'field_type' => 'text'
					,'channel_id' => 2
					,'field_id' => 3
				)
				,'channel_2_field_2' => array(
					'field_name' => 'channel_2_field_2'
					,'field_label' => 'Channel 2 field 2'
					,'channel_name' => 'channel_2'
					,'channel_title' => 'Channel 2'
					,'field_type' => 'text'
					,'channel_id' => 2
					,'field_id' => 4
				)
			)
		);
		
		//$this->_subject->field_settings();
					  		
		//$this->assertTrue(count($this->_subject->data['channels']) > 0 && is_array($this->_subject->data['channels']));		
		//$this->assertTrue(count($this->_subject->data['fields']) > 0 && is_array($this->_subject->data['fields']));		
		//$this->assertTrue(isset($this->_subject->data['form_action']));
	}
	
	
}