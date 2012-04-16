<?php

	$cwd = dirname(__FILE__).DIRECTORY_SEPARATOR;

	// Load base class
	require_once($cwd.'cmsconf_base.php');

	/**
	 * CMS configuration loader class.
	 */
	class CmsConf {
		/**
		 * @var string The CMS-specific adapter class name.
		 */
		protected $_adapter_cls = null;
		
		/**
		 * @var CmsConf_Base The CMS-specific adapter instance.
		 */
		protected $_adapter_obj = null;
		
		/**
		 * @param string Pathname to CMS project, or filename to CMS config file.
		 */
		public function __construct($path=null){
			if($path){
				$this->load($path);
			}
		}
		
		/**
		 * @param string Pathname to CMS project, or filename to CMS config file.
		 * @return boolean True if CMS was loaded, or false otherwise.
		 */
		public function load($path){
			foreach(self::$adapters as $adapter_cls){
				if(!!($adapter_obj = $adapter_cls::identify($path))){
					$this->_adapter_cls = $adapter_cls;
					$this->_adapter_obj = $adapter_obj;
					return true;
				}
			}
			return false;
		}
		
		/**
		 * If method was not found, it must be at the adapter.
		 * @param string $name Method name.
		 * @param array $args Method arguments.
		 * @return mixed Result of call.
		 */
		public function __call($name, $args){
			$args = array_pad($args, 5, null);
			if($this->_adapter_obj)
				return $this->_adapter_obj->$name($args[0], $args[1], $args[2], $args[3], $args[4]);
		}
		
		/**
		 * @var array List of adapters class name.
		 */
		public static $adapters = array();
	}
	
	/**
	 * Creates a new CMS config loader for $path.
	 * @param string|null Pathname to CMS project, or filename to CMS config file, or leave it out to load() later on.
	 * @return CmsConf_CmsConfBase The new instance.
	 */
	function CmsConf($path=null){
		return new CmsConf($path);
	}
	
	// Load the rest of classes
	foreach(glob($cwd.'cmsconf_*.php') as $file){
		$adapter = require_once($file);
		if($adapter && $adapter!==true){
			CmsConf::$adapters[] = $adapter;
		}
	}
	
?>