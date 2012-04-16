<?php

	class CmsConf_Joomla implements CmsConf_AdapterBase {
		
		public static function identify($pathname){
			if(is_dir($pathname)){
				if(substr($pathname, -1) != DIRECTORY_SEPARATOR){
					$pathname .= DIRECTORY_SEPARATOR;
				}
				$pathname .= 'configuration.php';
			}
			if(is_file($pathname)){
				$data = file_get_contents($pathname);
				if(strpos($data, 'JConfig')!==false){
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
			// process joomla config
			do{ $__class = 'JConfig_'.mt_rand(); }while(class_exists($__class));
			ob_start();
			eval(str_replace(
				array('<?php', 'JConfig'),
				array('', $__class),
				$__filedata
			));
			$this->_config = array_merge(get_object_vars(new $__class()), get_defined_vars());
			unset($this->_config['__filename']);
			unset($this->_config['__filedata']);
			unset($this->_config['__class']);
			$buf = ob_end_clean();
			if($buf!=''){
				trigger_error('Some data has been written unexpectedly during parsing: '.$buf, E_USER_WARNING);
			}
		}
		
		public function type(){
			return 'joomla';
		}
		
		public function version(){
			static $version = null;
			if(!$version){
				// find joomla version (v1.0, v1.7)
				$vfile = dirname($this->_fname).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'version.php';
				if(!file_exists($vfile)){
					// find joomla version (v1.5, v1.6)
					$vfile = dirname($this->_fname).DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'joomla'.DIRECTORY_SEPARATOR.'version.php';
					if(!file_exists($vfile)){
						// find joomla version (v2.5)
						$vfile = dirname($this->_fname).DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'cms'.DIRECTORY_SEPARATOR.'version'.DIRECTORY_SEPARATOR.'version.php';
					}
				}
				if(file_exists($vfile)){
					$fh = fopen($vfile, 'r');
					$fd = fread($fh, 1200);
					fclose($fh);
					preg_match('/\\$RELEASE\\s*=\\s*\'([0-9\\.]*)\';.*\\$DEV_LEVEL\s*=\\s*\'([0-9\\.]*)\';/s', $fd, $fd);
					$version = $fd[1].'.'.(int)$fd[2];
				}
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
				$cache = (object)array(
					'name' => $this->raw('db'),
					'type' => $this->raw('dbtype'),
					'user' => $this->raw('user'),
					'pass' => $this->raw('password'),
					'host' => $this->raw('host'),
					'prfx' => $this->raw('dbprefix'),
				);
			}
			return $cache;
		}

		public function ftp($cached=true){
			static $cache = null;
			if(!$cache || !$cached){
				$cache = (object)array(
					'host' => $this->raw('ftp_host'),
					'port' => $this->raw('ftp_port'),
					'user' => $this->raw('ftp_user'),
					'pass' => $this->raw('ftp_pass'),
					'root' => $this->raw('ftp_root'),
				);
			}
			return $cache;
		}
		
	}
	
	return 'CmsConf_Joomla';
	
?>