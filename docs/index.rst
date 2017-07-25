===========================
Modular Extensions Revamped
===========================

- :doc:`License Agreement <license>`
.. toctree::
	:titlesonly:
	:maxdepth: 1

	overview/index
	installation/index
	reference/index
	php/index	

Overview
========

HMVC stands for Hierarchical Model View Controller 

Modular Extensions makes the CodeIgniter PHP framework modular. Modules are groups of independent components, typically model, controller and view, arranged in an application modules sub-directory that can be dropped into other CodeIgniter applications.

Modular Extensions Revamped - HMVC-RV
*************************************

External hyperlinks, like `Modular Extensions - HMVC`__.

.. _HMVC: https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/ 
__ HMVC_

*Why?*
------

While attempting to create a `module template`__, functionality that's available in the current version of CodeIgniter *would not* work in the module.  The HMVC was **missing** some CodeIgniter functionality that has existed since v2.0.1_, such as:

.. _third-party-submodule: https://github.com/invitecomm/third-party-submodule
__ third-party-submodule_

.. //- Applying the sub-class prefix option when loading classes (v2.0.0_)
.. //- Multiple Environments (v2.0.1_)
.. //- Support for environment-specific configuration files (v2.0.1_)

:CodeIgniter v2.0.0_:
	- Applying the sub-class prefix option when loading classes
:CodeIgniter v2.0.1_:
	- Multiple Environments
	- Support for environment-specific configuration files

.. _v2.0.0: https://codeigniter.com/user_guide/changelog.html#version-2-0-0
.. _v2.0.1: https://codeigniter.com/user_guide/changelog.html#version-2-0-1

This led to the discovery that the current `Wiredesignz`__ version goes back to **before** CodeIgniter v2.0.0_ was released.  This can be seen throughout the code when, compared against later versions of CodeIgniter.  Some of the design and architectural changes in CodeIgniter are not reflected in the HMVC_. [#]_
	
__ HMVC_

Goals of this Project
---------------------

- Improved Documentation
- Improved Functionality
- Multi-Environment Support
- Hosted on GitHub_

.. _GitHub: https://github.com/invitecomm/codeigniter-modular-extensions-hmvc

.. toctree::
	:hidden:
   
	license

.. [#] Sincere appreciation goes towards `Wiredesignz`__ for the original code, the contributing developers, and all those who have maintained the HMVC_ to support later versions of CodeIgniter.

__ HMVC_
