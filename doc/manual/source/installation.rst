============
Installation
============

Requirements
============

Runtime requirements:

    * PHP 5.3 or above
    * MongoDB extension from PECL ( pecl install mongodb )
    
Development requirements:

    * Phing `http://www.phing.info <http://www.phing.info>`_
    * PHPUnit `https://github.com/sebastianbergmann/phpunit/ <https://github.com/sebastianbergmann/phpunit/>`_
    * Set the php inifile option: phar.readonly = false 

Building the Morph.phar file
============================

1. Clone (or fork) the repo - git://github.com/a-musing-moose/morph.git
2. Install all development dependencies
3. Use phing to build the morph.phar package::

    $ phing package

Documentation
=============

Documentation is created using Sphinx.  To generate the HTML docs, ensure you have sphinx installed and
do the following::

    $ cd docs/manual/
    $ make html && x-www-browser build/html/index.html
    
There are also make targets for ePub, pdf and a number of other formats.