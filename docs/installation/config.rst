#########################
Module Path Configuration
#########################

The Default Path
----------------

The module path defaults to **APPPATH/modules/**.  

Custom Module Paths
-------------------

Custom module paths are set using the ``$config['modules_locations']`` configuration directive in the **application/config.php** file.

.. note:: Multiple module paths **may** be configured.

::

	$config['modules_locations'] = array(
		APPPATH.'modules/'	=> '../modules/',
		APPPATH.'more_modules/'	=> '../more_modules/',
	);
	
.. important:: Modules with the **same name** that exist in multiple locations *may* have unexpected results.  Resources are loaded in the order found in the the configuration directive ``$config['modules_locations']``.  *The last resource found is the one that is used.*
