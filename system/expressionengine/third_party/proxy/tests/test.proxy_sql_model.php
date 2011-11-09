<?php

	require_once PATH_THIRD .'proxy/models/proxy_sql.php';
	
	class Test_proxy_sql_model extends Testee_unit_test_case{
		
		
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
			$this->_model = new Proxy_sql();	
		}
		
		/* --------------------------------------------------------------
		 * HELPER METHODS
		 * ------------------------------------------------------------ */
		
		/* --------------------------------------------------------------
		 * TEST METHODS
		 * ------------------------------------------------------------ */
		 public function test_get_entry_info()
		 {
		 	// $entry_info = $this->_model->get_entry_info(1);
		 	// $this->assertIsA($entry_info, 'array');
		 	// $this->assertTrue(isset($entry_info['site_id']));
		 	// $this->assertTrue(isset($entry_info['channel_id']));
		 	// $this->assertTrue(isset($entry_info['field_id']));
		 	// $this->assertTrue(isset($entry_info['group_id']));
		 	// $this->assertTrue(isset($entry_info['field_name']));
		 	// $this->assertTrue(isset($entry_info['field_label']));
		 	// $this->assertTrue(isset($entry_info['field_type']));
		 }


		
		
	}