==========
phpautodoc
==========

.. contents::
   :local:
   :depth: 2

phpautomodule
=============

Class Only
----------

.. phpautomodule::
   :filename: sample/function.php
   :undoc-members:
   
Class & Members
---------------

.. phpautomodule::
   :filename: sample/function.php
   :members:
   :undoc-members:

phpautoclass
============

Named Class
-----------

.. phpautoclass:: anotherClass
	:filename: sample/function.php
	:members:
	:undoc-members:
	
Auto Detect
-----------

*Does not work correctly*

.. phpautoclass:: *
	:filename: sample/function.php
	:members:
	:undoc-members:

phpautofunction
===============

Named Function
--------------

.. phpautofunction:: count
	:filename: sample/phpdoc.php
