<?php

	class CmsConf_K2F implements CmsConf_AdapterBase {
		
		public static function identify($pathname){
			if(is_dir($pathname)){
				if(substr($pathname, -1) != DIRECTORY_SEPARATOR){
					$pathname .= DIRECTORY_SEPARATOR;
				}
				$pathname .= 'config.php';
			}
			if(is_file($pathname)){
				$data = file_get_contents($pathname);
				if(strpos($data, 'K2F')!==false){
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
			// process K2F config
			$matches = array();
			if(preg_match_all('/\'(.+)\'\\s*=>\\s*(.+)\\s*,/', $__filedata, $matches)){
				$matches = array_combine($matches[1], $matches[2]);
				foreach($matches as $key=>$val){
					$this->_config[$key] = $val;
				}
			}
		}
		
		public function type(){
			return 'k2f';
		}
		
		public function version(){
			static $version = null;
			if(!$version){
				$vfile = dirname($this->_fname).DIRECTORY_SEPARATOR.'boot.php';
				if(file_exists($vfile)){
					$fh = fopen($vfile, 'r');
					$fd = fread($fh, 800);
					fclose($fh);
					if(preg_match('/define\\(\\s*\'K2F\'\\s*,\\s*\'(.+)\'\\s*\\);/', $fd, $fd))
						$version = $fd[1];
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
					'name' => $this->raw('DB_NAME'),
					'type' => $this->raw('DB_TYPE'),
					'user' => $this->raw('DB_USER'),
					'pass' => $this->raw('DB_PASS'),
					'host' => $this->raw('DB_HOST'),
					'prfx' => $this->raw('DB_PRFX'),
				);
			}
			return $cache;
		}
		
		public function ftp($cached=true){
			static $cache = null;
			if(!$cache || !$cached){
				$cache = (object)array(
					'host' => $this->raw('FTP_HOST'),
					'port' => 21,
					'user' => $this->raw('FTP_USER'),
					'pass' => $this->raw('FTP_PASS'),
					'root' => '',
				);
			}
			return $cache;
		}
		
	}
	
	return 'CmsConf_K2F';
	
?>