<?php

	class CmsConf_DbBase {
		/**
		 * @var string Database name (eg: 'project_wp').
		 */
		public $name;
		
		/**
		 * @var string Database type (eg: 'mysql').
		 */
		public $type;
		
		/**
		 * @var string Database username.
		 */
		public $user;
		
		/**
		 * @var string Database password.
		 */
		public $pass;
		
		/**
		 * @var string Hostname/ip of DB server.
		 */
		public $host;
		
		/**
		 * @var string Prefix of tables used by CMS.
		 */
		public $prfx;
		
	}

	class CmsConf_FtpBase {
		
		/**
		 * @var string FTP server hostname/ip.
		 */
		public $host;
		
		/**
		 * @var string FTP server port.
		 */
		public $port;
		
		/**
		 * @var string FTP username.
		 */
		public $user;
		
		/**
		 * @var string FTP password.
		 */
		public $pass;
		
		/**
		 * @var string FTP root directory.
		 */
		public $root;
	}

	interface CmsConf_Base {
		
		/**
		 * @return string CMS name.
		 */
		public function type();
		
		/**
		 * @return string CMS version.
		 */
		public function version();
		
		/**
		 * Returns the raw configuration value for a key.
		 * @param string $name Configuration key name.
		 * @param mixed $default (Optional) Value returned if configuration was not set.
		 * @return mixed The value (or $deault, if nonexistent).
		 */
		public function raw($name, $default=null);
		
		/**
		 * @return array All raw configuration entries.
		 */
		public function rawlist();
		
		/**
		 * @param boolean $cached Whether you want cached results (faster) or not (fresh, but slower).
		 * @return CmsConf_DbBase Database configuration details.
		 */
		public function db($cached=true);
		
		/**
		 * @param boolean $cached Whether you want cached results (faster) or not (fresh, but slower).
		 * @return CmsConf_FtpBase FTP configuration details.
		 */
		public function ftp($cached=true);
				
	}

	interface CmsConf_AdapterBase extends CmsConf_Base {
		
		/**
		 * @param string Pathname to CMS folder or config file.
		 * @return boolean True if adapter recognized CMS, or false otherwise.
		 */
		public static function identify($pathname);
		
		/**
		 * Construct new config loader given config file.
		 * @param string $filename Full path to existing config file.
		 * @param string $filedata Contents of the config file.
		 */
		public function __construct($filename, $filedata);
		
	}
	
	interface CmsConf_CmsConfBase extends CmsConf_Base {
				
		/**
		 * @param string Pathname to CMS project, or filename to CMS config file.
		 */
		public function load($path);
		
	}
	
?>