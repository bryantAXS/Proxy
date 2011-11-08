<?php

	require_once PATH_THIRD .'proxy/models/proxy_field_settings_model.php';
	
	class Test_proxy_field_settings_model extends Testee_unit_test_case{
		
		
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
			$this->_model = new Proxy_field_settings_model();	
		}
		
		/* --------------------------------------------------------------
		 * HELPER METHODS
		 * ------------------------------------------------------------ */
		
		/* --------------------------------------------------------------
		 * TEST METHODS
		 * ------------------------------------------------------------ */

		public function test_construct()
		{
			$this->assertNotNull($this->_model->site_id);
			$this->assertNotNull($this->_model->cache);
		}
		
		public function test_get_settings()
		{
			$this->assertIsA($this->_model->get_settings(1,1),'array', 'Channel 1 Field 1 does not have settings. Please try saving your channel field settings for the first time.');
		}

		public function test_get_channel_field_setting()
		{
			$this->_model->get_settings();
			$this->assertNotEqual($this->_model->get_channel_field_setting(1,1), FALSE, 'Channel 1 Field 1 does not appear to have settings. Please try saving your channel field settings for the first time.');
		}

		public function test_insert_new_field_settings()
		{
			
		}
		
		
	}