
CmsConfLoader
=============

PHP ConfLoader is a small library for reading configuration files of different kinds of CMSes or frameworks at the same time.
You just point it to the project directory and it will try to guess the framework type and version, as well as read the settings as well.

Usage
-----

The following example will connect to a database that is being used by an existing CMS project:

    require 'ConfLoader/src/cmsconf.php';

    $conf = CmsConf('/home/MyProject/public_html/');

    $db = new PDO(
		$conf->db()->type.':host='.$conf->db()->host.';dbname='.$conf->db()->name.';',
		$conf->db()->user,
		$conf->db()->pass
	);
	

Tests
-----

The script in [tests/index.php][tests/index.php] as able to load configuration from files in `tests/testdata`.
This directory will contain at least one sample config file for each supported CMS.

Contributing
============
Feel free to contribute bug fixes as well as adapters for CMSes not supported yet.

In the case of contributing adapters, please also include a test config file.


Report Issues/Bugs
==================
[Bugs](https://github.com/uuf6429/ConfLoader/issues)
