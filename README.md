Agile Grapher
=============

This is a demonstration project to test out the Silex framework in a semi real
world application.

Prerequisites
-------------

### [Sqlite3](http://www.sqlite.org/) ( Tested on 3.7.7-2 )

 * sudo apt-get install sqlite3
 * sudo apt-get install php5-sqlite
 * sqlite3 --version

### [Pear](http://pear.php.net/) ( Tested on 1.9.4 )

 * sudo apt-get install php-pear
 * sudo pear upgrade PEAR

### Doctrine2

 * sudo pear channel-discover pear.doctrine-project.org
 * sudo pear install pear.doctrine-project.org/DoctrineORM
 * doctrine --version

### [PHPUnit](http://www.phpunit.de) ( Tested on 3.6 )

 * sudo pear channel-discover pear.phpunit.de
 * sudo apt-get install php-pear
 * sudo pear install pear.phpunit.de/PHPUnit
 * phpunit --version

### [Phing](http://http://www.phing.info) ( Tested on 2.4.9 )

 * sudo pear channel-discover pear.phing.info
 * sudo pear install phing/phing
 * phing -version


Installation
------------
 1. git clone git://github.com/afoozle/agilegrapher.git
 2. cd agilegrapher
 3. git submodule sync
 4. git submodule init
 5. git submodule update
 6. pushd src/library/Silex/ && ./update_vendors.sh && popd
 7. phing migrate test


Note
----
The webserver user will need WRITE access to both the agilegrapher.db file and it's containing directory.
For example:

sudo chgrp www-data db db/agilegrapher.db


