<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * Modular Extensions Revamped - HMVC-RV
 *
 * Revamped version of the Wiredesignz Modular Extensions - HMVC, 
 * orignally adapted from the CodeIgniter Core Classes.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
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
 
/* load MX core classes */
require_once dirname(__FILE__).'/Lang.php';
require_once dirname(__FILE__).'/Config.php';


class CI
{
	public static $APP;
	
	public function __construct() {
		
		/* assign the application instance */
		self::$APP = CI_Controller::get_instance();
		
		global $LANG, $CFG;
		
		/* re-assign language and config for modules */
		if ( ! $LANG instanceof MX_Lang) $LANG = new MX_Lang;
		if ( ! $CFG instanceof MX_Config) $CFG = new MX_Config;
	}
}

/* create the application object */
new CI;