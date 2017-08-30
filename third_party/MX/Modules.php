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
 
(defined('EXT')) OR define('EXT', '.php');

global $CFG;

/* get module locations from config settings or use the default module location and offset */
is_array(Modules::$locations = $CFG->item('modules_locations')) OR Modules::$locations = array(
	APPPATH.'modules/' => '../modules/',
);

/* PHP5 spl_autoload */
spl_autoload_register('Modules::autoload');


class Modules
{
	public static $routes, $registry, $locations;
	
	/**
	* Run a module controller method
	* Output from module is buffered and returned.
	**/
	public static function run($module) 
	{	
		$method = 'index';
		
		if(($pos = strrpos($module, '/')) != FALSE) 
		{
			$method = substr($module, $pos + 1);		
			$module = substr($module, 0, $pos);
		}

		if($class = self::load($module)) 
		{	
			if (method_exists($class, $method))	{
				ob_start();
				$args = func_get_args();
				$output = call_user_func_array(array($class, $method), array_slice($args, 1));
				$buffer = ob_get_clean();
				return ($output !== NULL) ? $output : $buffer;
			}
		}
		
		log_message('error', "Module controller failed to run: {$module}/{$method}");
	}
	
	/** Load a module controller **/
	public static function load($module) 
	{
		(is_array($module)) ? list($module, $params) = each($module) : $params = NULL;	
		
		/* get the requested controller class name */
		$alias = strtolower(basename($module));

		/* create or return an existing controller from the registry */
		if ( ! isset(self::$registry[$alias])) 
		{
			/* find the controller */
			list($class) = CI::$APP->router->locate(explode('/', $module));
	
			/* controller cannot be located */
			if (empty($class)) return;
	
			/* set the module directory */
			$path = APPPATH.'controllers/'.CI::$APP->router->directory;
			
			/* load the controller class */
			$class = $class.CI::$APP->config->item('controller_suffix');
			self::load_file(ucfirst($class), $path);
			
			/* create and register the new controller */
			$controller = ucfirst($class);	
			self::$registry[$alias] = new $controller($params);
		}
		
		return self::$registry[$alias];
	}
	
	/** Library base class autoload **/
	public static function autoload($class) 
	{	
		/* don't autoload CI_ prefixed classes or those using the config subclass_prefix */
		if (strstr($class, 'CI_') OR strstr($class, config_item('subclass_prefix'))) return;

		/* autoload Modular Extensions MX core classes */
		if (strstr($class, 'MX_')) 
		{
			if (is_file($location = dirname(__FILE__).'/'.substr($class, 3).EXT)) 
			{
				include_once $location;
				return;
			}
			show_error('Failed to load MX core class: '.$class);
		}
		
		/* autoload core classes */
		if(is_file($location = APPPATH.'core/'.ucfirst($class).EXT)) 
		{
			include_once $location;
			return;
		}		
		
		/* autoload library classes */
		if(is_file($location = APPPATH.'libraries/'.ucfirst($class).EXT)) 
		{
			include_once $location;
			return;
		}		
	}

	/** Load a module file **/
	public static function load_file($file, $path, $type = 'other', $result = TRUE)	
	{
		$file = str_replace(EXT, '', $file);		
		$location = $path.$file.EXT;
		
		if ($type === 'other') 
		{			
			if (class_exists($file, FALSE))	
			{
				log_message('debug', "File already loaded: {$location}");				
				return $result;
			}	
			include_once $location;
		} 
		else 
		{
			/* load config or language array */
			include $location;

			if ( ! isset($$type) OR ! is_array($$type))				
				show_error("{$location} does not contain a valid {$type} array");

			$result = $$type;
		}
		log_message('debug', "File loaded: {$location}");
		return $result;
	}

	/** 
	* Find a file
	* Scans for files located within modules directories.
	* Also scans application directories for models, plugins and views.
	* Generates fatal error if file not found.
	**/
	public static function find($file, $module, $base) 
	{
		$segments = explode('/', $file);

		$file = array_pop($segments);
		$file_ext = (pathinfo($file, PATHINFO_EXTENSION)) ? $file : $file.EXT;
		
		$path = ltrim(implode('/', $segments).'/', '/');	
		$module ? $modules[$module] = $path : $modules = array();
		
		if ( ! empty($segments)) 
		{
			$modules[array_shift($segments)] = ltrim(implode('/', $segments).'/','/');
		}	

		foreach (Modules::$locations as $location => $offset) 
		{					
			foreach($modules as $module => $subpath) 
			{			
				$fullpath = $location.$module.'/'.$base.$subpath;
				
				if ($base == 'libraries/' OR $base == 'models/')
				{
					if(is_file($fullpath.ucfirst($file_ext))) return array($fullpath, ucfirst($file));
				}
				else
				/* load non-class files */
				if (is_file($fullpath.$file_ext)) return array($fullpath, $file);
			}
		}
		
		return array(FALSE, $file);	
	}
	
	/** Parse module routes **/
	public static function parse_routes($module, $uri) 
	{
		/* load the route file */
		if ( ! isset(self::$routes[$module])) 
		{
			if (list($path) = self::find('routes', $module, 'config/'))
			{
				$path && self::$routes[$module] = self::load_file('routes', $path, 'route');
			}
		}

		if ( ! isset(self::$routes[$module])) return;
			
		/* parse module routes */
		foreach (self::$routes[$module] as $key => $val) 
		{						
			$key = str_replace(array(':any', ':num'), array('.+', '[0-9]+'), $key);
			
			if (preg_match('#^'.$key.'$#', $uri)) 
			{							
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE) 
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}
				return explode('/', $module.'/'.$val);
			}
		}
	}

    // --------------------------------------------------------------------

	/**
	 * Format the full element path.
	 *
	 * @see mx_element_pathinfo() for information on the $elements array.
	 * @todo Include ability for ucfirst to support library calsses.
	 *
	 * @param	string  $path The absolute path.
	 * @param	string  $subdir Subdirectory containing the elements.
	 * @param	array  $elements The element path info.
	 * @param	bool  $subclass Use filename with the subclass_prefix
	 * @return	string filename OR path/to/filename
	 */		
	public static  function mx_element_path($path, $subdir, $elements, $subclass=FALSE) {
	
	    if ($subclass) {
	        $filename = $elements['subclass'] . '.' . $elements['extension'];
	    } else {
	        $filename = $elements['filename'] . '.' . $elements['extension'];
	    }
	
        $data =   join(
                        DIRECTORY_SEPARATOR,
                        array(
                            $path,
                            $subdir,
                            $elements['dirname'],
                            $filename
                        )
                    );
        return $data;

	}

	// --------------------------------------------------------------------
	/**
	 * Cleanup the element to be loaded.
	 *
     * A multi-use function to process and cleanup the element path provided to 
     * the loader class.  Providing an associative array of information about 
     * the file to be loaded, and provides additional information to meet CI 
     * naming requirements.
	 *
	 * @param	string  $path The element passed to the loader class.
	 * @param	string  $type The element type, such as 'helper' or '_helper'.
	 * @return	array  
	 */	
	public static function mx_element_pathinfo($path, $type=NULL) {
	
		// Return the supplied path information
		$data = pathinfo($path);
		
		// Set the file extension.  Normally not passed to the loader.
		$data['extension'] = 'php';

		// Affix underscore to the type, if needed.
		if ($type) {
            $type = ($type[0] === '_') ? $type : '_' . $type;
        }
        
		// Lower Case
		$data['filename'] = strtolower($data['filename']);

		// Cleanup the file name
		// url || url_helper || url_helper_helper = url_helper
		// url.php || url_helper_helper.php.php = url_helper
		$regex = '#(' . $type . ')*?(\.php)*?$#i';
		$data['filename'] = preg_replace($regex,'',$data['filename']).$type;

		// UCFirst
		$data['ucf'] = ucfirst($data['filename']);
		
		// Affix the extension subclass prefix 
		// url_helper -> MY_url_helper
		 $data['subclass'] = config_item('subclass_prefix').$data['filename'];
		
		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Format the element to track it's loaded status.
	 *
	 * @see mx_element_pathinfo() for information on the $elements array.
	 *
	 * @param	array  $elements The element path info.
	 * @return	string filename OR path/to/filename
	 */	
	public static  function mx_element_track($elements) {
	
	    if ($elements['dirname'] === '.') {
            $data = $elements['filename'];
	    } else {
            $data =   join(
                            DIRECTORY_SEPARATOR,
                            array(
                                $elements['dirname'],
                                $elements['filename']
                            )
                        );
        }

        return $data;

	}

	// --------------------------------------------------------------------

	/**
	 * List of paths to load resources from.
	 *
	 * Creates a list of paths, starting with the module paths.  It's 
	 * used to replace: $_ci_library_paths $_ci_helper_paths $_ci_model_paths
	 *
	 * @todo Add parameter to change the order of operation.  
	 *
	 * @param	bool  $basepath Include the CI BASEPATH in returned array.
	 * @return	array
	 */	
	public static  function mx_module_paths($basepath=TRUE) {

        // Module Paths
        foreach (Modules::$locations AS $path => $module) {
            $data[] =   join(
                            DIRECTORY_SEPARATOR,
                            array(
                                $path, 
                                CI::$APP->router->fetch_module()
                            )
                        );
        }
        
        // Package Paths
        $package_paths = get_instance()->load->get_package_paths($basepath);
	    
	    // Merge The Arrays
	    $paths = array_merge($data, $package_paths);
	    
	    return $paths;
	
	}

	// --------------------------------------------------------------------
	
}