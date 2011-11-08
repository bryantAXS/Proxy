<?php

	require_once PATH_THIRD .'proxy/models/proxy_settings_model.php';
	
	class Test_proxy_settings_model extends Testee_unit_test_case{
		
		
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
			$this->_model = new Proxy_settings_model();	
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
			$this->assertIsA($this->_model->get_proxy_settings(),'array');
		}
		
	}