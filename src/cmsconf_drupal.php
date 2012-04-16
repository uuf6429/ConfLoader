<?php

	class CmsConf_Drupal implements CmsConf_AdapterBase {
		
		public static function identify($pathname){
			if(is_dir($pathname)){
				if(substr($pathname, -1) != DIRECTORY_SEPARATOR){
					$pathname .= DIRECTORY_SEPARATOR;
				}
				$pathname .= 'sites'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'settings.php';
			}
			if(is_file($pathname)){
				$data = file_get_contents($pathname);
				if(strpos($data, 'Drupal')!==false){
					return new self($pathname, $data);
				}
			}
			return false;
		}
		
		protected $_config = array();
		protected $_fname = '';
		
		public function __construct($__filename, $__filedata){
			// TODO Constructor should not be doing any work.
			$this->_fname = $__filename;
			// process drupal config
			ob_start();
			eval(str_replace(
				array('<?php', 'ini_set'),
				array('', __CLASS__.'::_nop'),
				$__filedata
			));
			$this->_config = get_defined_vars();
			unset($this->_config['__filename']);
			unset($this->_config['__filedata']);
			$buf = ob_end_clean();
			if($buf!=''){
				trigger_error('Some data has been written unexpectedly during parsing: '.$buf, E_USER_WARNING);
			}
		}
		
		public function type(){
			return 'drupal';
		}
		
		public function version(){
			static $version = null;
			if(!$version){
				// TODO Find drupal version
			}
			return $version;
		}

		public function raw($name, $default=null){
			return isset($this->_config[$name]) ? $this->_config[$name] : $default;
		}

		public function rawlist(){
			return $this->_config;
		}
		
		public function db($cached=true){
			static $cache = null;
			if(!$cache || !$cached){
				$db = $this->raw('databases');
				$cache = (object)array(
					'name' => $db['default']['default']['database'],
					'type' => $db['default']['default']['driver'],
					'user' => $db['default']['default']['username'],
					'pass' => $db['default']['default']['password'],
					'host' => $db['default']['default']['host'],
					'prfx' => $db['default']['default']['prefix'],
				);
			}
			return $cache;
		}
		
		public function ftp($cached=true){ // TODO Ask drupal people how to get this
			static $cache = null;
			if(!$cache || !$cached){
				$cache = (object)array(
					'host' => '',
					'port' => '',
					'user' => '',
					'pass' => '',
					'root' => '',
				);
			}
			return $cache;
		}

		###  Utility methods ###
		// TODO Use a better way without statics, by passing objects to loaded config file.
		
		/**
		 * Function used to replace functionality with no operation.
		 */
		public static function _nop(){}
		
	}
	
	return 'CmsConf_Drupal';
	
?>