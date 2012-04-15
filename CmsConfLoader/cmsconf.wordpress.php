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
		
		protected $_version = '';
		
		protected $_config = array();
		
		public function __construct($filename, $filedata){
			// find wordpress version
			$vfile = dirname($filename).DIRECTORY_SEPARATOR.'wp-includes'.DIRECTORY_SEPARATOR.'version.php';
			if(file_exists($vfile)){
				$fh = fopen($vfile, 'r');
				$fd = fread($fh, 300);
				fclose($fh);
				preg_match('/\\$wp_version\\s*=\\s*\'([0-9\\.]*)\';/', $fd, $fd);
				$this->_version = $fd[1];
			}
			// process wordpress config
			do{ $cls = 'JConfig_'.mt_rand(); }while(class_exists($cls));
			$replace = array(
				'<?php' => '',
				'define(' => __CLASS__.'::_rec_rec(',
				'require_once(ABSPATH . ' => __CLASS__.'::_rec_nop(',
			);
			$filedata = str_replace(array_keys($replace), array_values($replace), $filedata);
			ob_start();
			self::_rec_bgn();
			eval($filedata);
			$this->_config = self::_rec_end();
			$this->_config['table_prefix'] = $table_prefix;
			$buf = ob_end_clean();
			if($buf!=''); // error?!
		}
		
		public function type(){
			return 'wordpress';
		}
		
		public function version(){
			return $this->_version;
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