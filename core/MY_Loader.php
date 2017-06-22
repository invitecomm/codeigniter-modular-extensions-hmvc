<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * Modular Extensions Revamped - HMVC-RV
 *
 * Revamped version of the Wiredesignz Modular Extensions - HMVC.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2015 Wiredesignz
 * Copyright (c) 2017 INVITE Communications Co., Ltd.
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
 **/

// ----------------------------------------------------------------------

/**
  * Load the MX_Loader Class
  *
  * Object inheritance of the public and protected methods from the Loader Core
  * Class.  MX_Loader extends CI_Loader
  */
require APPPATH."third_party/MX/Loader.php";

// ----------------------------------------------------------------------

/**
  * Extend the Loader Core Class
  * 
  * When adding functionality to an existing library, the normal method is to 
  * extend the parent class in CodeIgniter.  MY_Loader extends CI_Loader
  * 
  * The class hierarchy effectivly results in the following object 
  * inheritance.  MY_Loader extends MX_Loader extends CI_Loader
  *
  * @package	third_party/MX/Loader.php
  * @subpackage MX_Loader
  */
class MY_Loader extends MX_Loader {
	// Method overriding is handled by MX_Loader
	// Methods added here will overide both MX_Loader and CI_Loader


	public function helper($helpers = array())
	{
	
		// base_replace
		// base_extend
		// hmvc_replace
		// hmvc_extend
		
		is_array($helpers) OR $helpers = array($helpers);
		foreach ($helpers as &$helper)
		{
			$filename = basename($helper);
			$filepath = ($filename === $helper) ? '' : substr($helper, 0, strlen($helper) - strlen($filename));
			$filename = strtolower(preg_replace('#(_helper)?(\.php)?$#i', '', $filename)).'_helper';
			$helper   = $filepath.$filename;

			if (isset($this->_ci_helpers[$helper]))
			{
				continue;
			}

			// Is this a helper extension request?
			$ext_helper = config_item('subclass_prefix').$filename;
			$ext_loaded = FALSE;
			foreach ($this->_ci_helper_paths as $path)
			{
				if (file_exists($path.'helpers/'.$ext_helper.'.php'))
				{
					include_once($path.'helpers/'.$ext_helper.'.php');
					$ext_loaded = TRUE;
				}
			}

			// If we have loaded extensions - check if the base one is here
			if ($ext_loaded === TRUE)
			{
				$base_helper = BASEPATH.'helpers/'.$helper.'.php';
				if ( ! file_exists($base_helper))
				{
					show_error('Unable to load the requested file: helpers/'.$helper.'.php');
				}

				include_once($base_helper);
				$this->_ci_helpers[$helper] = TRUE;
				log_message('info', 'Helper loaded: '.$helper);
				continue;
			}

			// No extensions found ... try loading regular helpers and/or overrides
			foreach ($this->_ci_helper_paths as $path)
			{
				if (file_exists($path.'helpers/'.$helper.'.php'))
				{
					include_once($path.'helpers/'.$helper.'.php');

					$this->_ci_helpers[$helper] = TRUE;
					log_message('info', 'Helper loaded: '.$helper);
					break;
				}
			}

			// unable to load the helper
			if ( ! isset($this->_ci_helpers[$helper]))
			{
				show_error('Unable to load the requested file: helpers/'.$helper.'.php');
			}
		}

		return $this;
	}
	/** Load a module helper **/
	public function blue($helper = array())
	{
		if (is_array($helper)) return $this->helpers($helper);

		if (isset($this->_ci_helpers[$helper]))	return;

		list($path, $_helper) = Modules::find(config_item('subclass_prefix').$helper.'_helper', $this->_module, 'helpers/');

		if ($path === FALSE) return parent::helper($helper);

		Modules::load_file($_helper, $path);
		$this->_ci_helpers[$_helper] = TRUE;
		return $this;
	}

}