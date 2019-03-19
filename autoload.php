<?php

class AutoloadDefault {
	
	private $web_config = null;
	
	private $load_map = array();
	
	private $classpath = array();

	private $nspath = array();
	
	private $ext = array('.php');
	/**
	 * Enter description here ...
	 * @param string $class_path
	 * @param string $web_xml
	 */
	public function __construct($class_path = false, $web_xml = 'web.xml') {
		
		if (!$class_path) {
			$class_path = __DIR__.'/..';
		}
		
		$this->web_config = $web_xml;
		$this->parseWebConfig();
		spl_autoload_register(array($this, 'autoload'));
		
		if (is_string($class_path)) {
			$cp = explode(";", $class_path);
			$this->addClassPath($cp);
		} else {
			$this->addClassPath($class_path);
		}
	}
	
	/**
	 * @param array|string $path
	 */
	public function addClassPath($path) {
		if (is_array($path)) {
			foreach($path as $key=>$val) {
				if (is_int($key)) {
					$this->classpath[] = $this->switchSeparator($val);
				} else {
					$this->nspath[$key] = $this->switchSeparator($val);
				}
			}
		} else {
			$this->classpath[] = $this->switchSeparator($path);
		}		
	}
	
	
	private function switchSeparator($source) {
		$target = '';
		if (strlen($source)) {
			$target = str_replace('\\', '/', trim($source));
			while ($target{strlen($target)-1} === '/') {
				$target = substr($target, 0, strlen($target)-1);
			}
		}
		return $target;
	}
	
	/**
	 * @param string|array $ext
	 */
	public function addExtentions($ext) {
		if (is_array($ext)) {
			foreach ($ext as $val) {
				/*
				if ($val[0] !== '.') {
					$val = '.'.$val;
				}
				$this->ext[] = $val;
				*/
				$this->addExtentions($val);
			}
		} else {
			$ext = trim($ext);
			if ($ext{0} !== '.') {
				$ext = '.'.$ext;
			}
			$this->ext[] = $ext;
		}
	}
	
	/**
	 * Enter description here ...
	 */
	protected function parseWebConfig() {
		if ($this->web_config !== null && file_exists($this->web_config)) {
			$xml = new DOMDocument();
			$xml->load($this->web_config);
			
			$root = $xml->documentElement;
			
			$childs = $root->childNodes;
			
			/* @var $child DOMNode */
			foreach ($childs as $child) {
				if ($child->nodeType == XML_ELEMENT_NODE && $child->nodeName == 'substitude') {
					$elements = $child->childNodes;
					/* @var $elm DOMElement */
					$source = '';
					$target = '';
					foreach ($elements as $elm) {
						if ($elm->nodeType == XML_ELEMENT_NODE) {
							if ($elm->nodeName == 'source') {
								$source = $elm->nodeValue;
							} else if ($elm->nodeName == 'target') {
								$target = $elm->nodeValue;
							}
						}
					}
					
					if ($source && $target) {
						$this->load_map[$target] = $source;
					}
				}
			}
		}
		
	}
	
	
	/**
	 * Enter description here ...
	 * @param string $name
	 * @throws Exception
	 */
	public function autoload($name) {
		//prn($name);
		if (isset($this->load_map[$name])) {
			$source = $this->load_map[$name];
			$info = explode('\\', $name);
			//prn($info);
			$cnt = count($info);
			$class = $info[$cnt-1];
			if ($class) {
				unset($info[$cnt-1]);
				$namespace = implode('\\', $info);
				$namespace = $namespace?$namespace.'\\':'';
				class_alias($source, $namespace.$class);
			}
		} else {
			$info = explode('\\', $name);
			$cnt = count($info);
			$class = $info[$cnt-1];
			if ($class && $cnt) {
				unset($info[$cnt-1]);
				$ns = implode('\\', $info);
				$dir = implode('/', $info);
				$loaded = false;
				foreach ($this->nspath as $key=>$val) {
					if (strpos($ns, $key) === 0) {
						if ($this->loadFile($val.'/'.$dir.'/'.$class)) {
							$loaded = true;
							break;
						} else {
							//$sw = $this->switchSeparator($key);
							$edir = $this->switchSeparator(substr($ns, strlen($key)));
							if ($this->loadFile($val.'/'.$edir.'/'.$class)) {
								$loaded = true;
								break;
							}							
						}
					}
				}
				if (!$loaded) {
					foreach ($this->classpath as $key=>$val) {
						if ($this->loadFile($val.'/'.$dir.'/'.$class)) {
							break;
						}
					}
				}
			}
		}
	}	
	
	
	protected function loadFile($filename) {
		$result = false;
		foreach($this->ext as $ext) {
			if (file_exists($filename.$ext)) {
				include_once ($filename.$ext);
				$result = true;
				break;
			}
		}
		return $result;
	}
	
	
	public function debug() {
		prn($this->nspath);
		prn($this->classpath);
		prn($this->ext);
		prn($this->load_map);
	}
	
}