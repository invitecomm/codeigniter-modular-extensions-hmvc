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

if ( ! function_exists('alpha_zulu'))
{
	function alpha_zulu() {
	    return 'application';
	}
}

	function alpha_two() {
	    return 'application';
	}


    function asset($path) {
        //return pathinfo($path);
        
        $asset = 'assets'. DIRECTORY_SEPARATOR . $path;
        $asset_path = preg_replace('#/+#','/',$asset);
        foreach (Modules::mx_module_paths() AS $module) {
        
            $myPath = $module . DIRECTORY_SEPARATOR . $asset_path;
            
            if (realpath($myPath)) {

                //$str = preg_replace(FCPATH, '', realpath($module));
                return base_url() . clean_path($myPath);
            /*} else {*/
                //$myPath = FCPATH . DIRECTORY_SEPARATOR . $asset_path;
                //return realpath($myPath);
            
                
            }

        }
        
        return base_url() . $asset_path;
        
        //return debug_backtrace();
        //return CI::$APP->router->fetch_module();
        
        //return base_url();
        
        
        //$clean = preg_replace('#/+#','/',$path);
        //return $clean;
        
        
    }
    
    function clean_path($path) {
    
        $front_controller = explode('/', realpath(FCPATH));
        $compate_path = explode('/', realpath($path));
        
        return implode('/', array_diff_key($compate_path, $front_controller));
    
    }