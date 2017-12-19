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
  * Extend, Override, Replace the Loader Core Class
  * 
  * When adding functionality to an existing library, the normal method is to 
  * extend the parent class in CodeIgniter.  MY_Loader extends CI_Loader
  * 
  * The class hierarchy effectivly results in the following object 
  * inheritance.  MY_Loader extends MX_Loader extends CI_Loader
  *
  * @package	third_party/MX/Loader.php
  *
  * @subpackage MX_Loader
  */
class MY_Loader extends MX_Loader {
	// Method overriding is handled by MX_Loader
	// Methods added here will overide both MX_Loader and CI_Loader
	// --------------------------------------------------------------------

	// --------------------------------------------------------------------

}