<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
error_reporting(-1);
ini_set('display_errors', 1);
/**
 * Modular Extensions Revamped - HMVC-RV
 *
 * Revamped version of the Wiredesignz Modular Extensions - HMVC, 
 * orignally adapted from the CodeIgniter Core Classes.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 * Copyright (c) 2011 Wiredesignz
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
 

/**
  * Override the Language Class
  * 
  * When adding functionality to an existing library, the normal method is to 
  * extend the parent class in CodeIgniter.  MY_Loader extends CI_Loader
  *
  * @package	third_party/MX/Lang.php
  * @subpackage MX_Llang
  */
class MX_Lang extends CI_Lang
{

	// --------------------------------------------------------------------

	/**
	 * Load Language
	 *
	 * @uses Modules::mx_element_path()
	 * @uses Modules::mx_element_pathinfo()
	 * @uses Modules::mx_element_track()
	 * @uses Modules::mx_module_paths()
	 *
	 * @param	string|array	$langfile	Language files
	 * @return	object
	 */
	public function load($langfile, $lang = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $_module = '')	
	{

		if (is_array($langfile))
		{
			foreach ($langfile as $value)
			{
				$this->load($value, $idiom, $return, $add_suffix, $alt_path);
			}

			return;
		}
		
        // Path Information
        $elements = Modules::mx_element_pathinfo($langfile, 'lang');

        // Tracking Name
        $tracking = Modules::mx_element_track($elements);

        // Get configured language
		if (empty($idiom) OR ! preg_match('/^[a-z_-]+$/i', $idiom))
		{
			$config =& get_config();
			$idiom = empty($config['language']) ? 'english' : $config['language'];
		}

        // Skip is already loaded
		if ($return === FALSE && 
		    isset($this->is_loaded[$tracking]) && 
		    $this->is_loaded[$tracking] === $idiom)
		{
			return;
		}
		
        // Load Languges
        foreach (array_reverse(Modules::mx_module_paths()) as $path) {

            // Full Path to File
    $file = Modules::mx_element_path($path,'language/'.$idiom,$elements);

				if (realpath($file)) {
                    include(realpath($file));
                    $found = TRUE;
				}        
        }

		if ($found !== TRUE)
		{
			show_error('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
		}

		if ( ! isset($lang) OR ! is_array($lang))
		{
			log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);

			if ($return === TRUE)
			{
				return array();
			}
			return;
		}

		if ($return === TRUE)
		{
			return $lang;
		}

		$this->is_loaded[$tracking] = $idiom;
		$this->language = array_merge($this->language, $lang);

        log_message(
            'info', 
            'Language file loaded: language/'.$idiom.'/'.$langfile
        );

		return TRUE;

	}

	// --------------------------------------------------------------------

}