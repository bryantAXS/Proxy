<?php

require_once PATH_THIRD .'proxy/mod.proxy.php';
// require_once PATH_THIRD .'proxy/tests/mocks/mock.proxy_sql_model.php';
//require_once PATH_THIRD .'proxy/tests/mocks/mock.proxy_settings_model.php';

class Test_proxy_mod extends Testee_unit_test_case{
	
	
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
		$this->_subject = new Proxy();	
	}
	
	/* --------------------------------------------------------------
	 * HELPER METHODS
	 * ------------------------------------------------------------ */
	
	/* --------------------------------------------------------------
	 * TEST METHODS
	 * ------------------------------------------------------------ */

	 function test_constructor(){
	 	
	 	$this->assertIsA($this->_subject->cache,'array','Cache is not being set');

	 }
	
	
}