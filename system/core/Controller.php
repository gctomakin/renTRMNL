<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Authenticate 
	 * 1: lessee
	 * 2: lessor
	 * 3: lessee or lessor
	 * 4: admin
	 * @var Int
	 */
	private static $auth;
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct($auth = "")
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
		$this->auth = $auth;
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
   * Checks Controller's method if user has authority, 
   * otherwise user will be redirect to the respected page
   * @param  String $method Name of the method
   * @return method or redirect
   */
  public function _remap($method) {
  	
  	if (!empty($this->auth)) { // If empty no need to check authority
  		$isLogin = false;
  		$role = "lessees";
  		switch ($this->auth) {
  			case 1: // Check Lessee logged in session
  				$isLogin = $this->session->has_userdata('logged_in');
  				break; 
  			case 2: // Check Lessor logged in session
  				$isLogin = $this->session->has_userdata('lessor_logged_in');
  				$role = "lessors";
  				break; 
  			case 3: // Check Lessor or Lessee logged in session
  				$isLogin = $this->session->has_userdata('lessor_logged_in') || $this->session->has_userdata('logged_in');
  				break;
  			case 4: // Check admin logged in session
  				$isLogin = $this->session->has_userdata('admin_logged_in');
  				$role = "admin";
  				break;			
  		}

	  	if ( // Check for login session except for :
	  		$method == "signinPage" ||
	  		$method == "signin"
	  	) {
	  		if ($isLogin) { // Check if login session already exist
	  			redirect($role);
	  			exit();
	  		}
	  	} else {
	  		if (!$isLogin && $this->input->is_ajax_request()) {
	  			echo json_encode(array('result' => '403')); // Returns Forbidden code if not signed in
	  			exit();
	  		} else if (!$isLogin) { // Redirect to signin page if not signed in
	  			redirect($role . "/signin-page");
	  			exit();
	  		}
	  	}
	  }
  	$this->$method();
  }
}
