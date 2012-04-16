<?php

	class CmsConf_Wordpress implements CmsConf_AdapterBase {
		
		public static function identify($pathname){
			if(is_dir($pathname)){
				if(substr($pathname, -1) != DIRECTORY_SEPARATOR){
					$pathname .= DIRECTORY_SEPARATOR;
				}
				$pathname .= 'wp-config.php';
			}
			if(is_file($pathname)){
				$data = file_get_contents($pathname);
				if(strpos($data, 'WordPress')!==false){
					return new self($pathname, $data);
				}
			}
			return false;
		}
		
		protected $_config = array();
		protected $_fname = '';
		
		public function __construct($__filename, $__filedata){
			$this->_fname = $__filename;
			// process wordpress config
			ob_start();
			self::_rec_bgn();
			eval(str_replace(
				array('<?php', 'define(', 'require_once(ABSPATH . '),
				array('', __CLASS__.'::_rec_rec(', __CLASS__.'::_rec_nop('),
				$__filedata
			));
			$this->_config = array_merge(self::_rec_end(), get_defined_vars());
			unset($this->_config['__filename']);
			unset($this->_config['__filedata']);
			$buf = ob_end_clean();
			if($buf!=''); // TODO Throw warning.
		}
		
		public function type(){
			return 'wordpress';
		}
		
		public function version(){
			static $version = null;
			if(!$version){
				$vfile = dirname($this->_fname).DIRECTORY_SEPARATOR.'wp-includes'.DIRECTORY_SEPARATOR.'version.php';
				if(file_exists($vfile)){
					$fh = fopen($vfile, 'r');
					$fd = fread($fh, 300);
					fclose($fh);
					preg_match('/\\$wp_version\\s*=\\s*\'([0-9\\.]*)\';/', $fd, $fd);
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
					'type' => 'mysql',
					'user' => $this->raw('DB_USER'),
					'pass' => $this->raw('DB_PASSWORD'),
					'host' => $this->raw('DB_HOST'),
					'prfx' => $this->raw('table_prefix'),
				);
			}
			return $cache;
		}
		
		public function ftp($cached=true){
			static $cache = null;
			if(!$cache || !$cached){
				$cache = (object)array(
					'host' => $this->raw('FTP_HOST'),
					'port' => $this->raw('FTP_SSL', false) ? 990 : 21,
					'user' => $this->raw('FTP_USER'),
					'pass' => $this->raw('FTP_PASS'),
					'root' => $this->raw('FTP_BASE'),
				);
			}
			return $cache;
		}

		###  Utility methods ###
		// TODO Use a better way without statics, by passing objects to loaded config file.
		
		protected static $_record = array();
		
		/**
		 * Function used to replace functionality with no operation.
		 */
		public static function _rec_nop(){}
		
		/**
		 * Begin recording settings.
		 */
		public static function _rec_bgn(){
			self::$_record = array();
		}

		/**
		 * Record a setting pair.
		 * @param string $key Setting key.
		 * @param mixed $val Setting value.
		 */
		public static function _rec_rec($key, $val){
			self::$_record[$key] = $val;
		}
		
		/**
		 * Stop recording and return captured settings.
		 * @return array List of settings.
		 */
		public static function _rec_end(){
			return self::$_record;
		}
	}
	
	
	return 'CmsConf_Wordpress';
	
?>