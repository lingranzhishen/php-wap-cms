<?php

if(!defined('DIR_SEP')) {
    define('DIR_SEP', DIRECTORY_SEPARATOR);
}

/**
 * Tiger 模板操作类
 * @category   Tiger
 * @package  Tiger
 * @subpackage  DB
 * @author    DzTemplate
 * @version   $Id$
 */
class Tiger_template extends Tiger_base {


    /**
     * the config options of Tiger_template Class
     * 
     * @var array
     * @access private
     */
    private $_options = array();
    
    /**
     * the class constructor
     * 
     * @access private
     * @return void
     */
    function __construct() {
        $this->_options = array(
            'template_dir' => 'templates'.DIR_SEP,  // The name of the directory where templates are located
            'cache_dir' => 'cache'.DIR_SEP,         // The name of the directory for cache files
            'left_delimiter' => '{',                // The left delimiter used for the template tags
            'right_delimiter' => '}',               // The right delimiter used for the template tags
            'compile_check' => true,                // This tells Tiger_template whether to check for recompiling or not
            'cache_lifetime' => 0                   // Number of seconds cached content will persist, 0 = never expires
        );
    }     
  
    
    /**
     * set the config options of Tiger_template by array
     * 
     * @param array $options 
     * @access public
     * @return void
     */
    public function setOptions(array $options) {
        foreach($options as $name => $value) {
            $this->set($name, $value);
        }
    }
    
    /**
     * assign value to the given option
     * 
     * @param string $name 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function set($name, $value) {
    	switch($name) {
    		case 'template_dir':
    			$value = $this->trimPath($value);
    			if(!file_exists($value)) {
    				$this->throwException("Template directory \"$value\" not found or have no access!");
    			}
    			$this->_options['template_dir'] = $value;
    			break;
    		case 'cache_dir':
    			$value = $this->trimPath($value);
    			if(!file_exists($value)) {
    				$this->throwException("Cache directory \"$value\" not found or have no access!");
    			}
    			chmod($value, 0777);
    			$this->_options['cache_dir'] = $value;
    			break;
    		case 'left_delimiter':
    			$this->_options['left_delimiter'] = preg_quote($value);
    			break;
    		case 'right_delimiter':
    			$this->_options['right_delimiter'] = preg_quote($value);
    			break;    			
    		case 'compile_check':
    			$this->_options['compile_check'] = (boolean) $value;
    			break;
    		case 'cache_lifetime':
    			$this->_options['cache_lifetime'] = (int) $value;
    			break;
    		default:
    			$this->throwException("Unknown config option \"$name\"");
    	}
    }    

  
    /**
     * throw a new exception with message
     * 
     * @param string $msg 
     * @access protected
     * @return void
     */
    protected function throwException($msg) {
        // throw new Exception($msg);
		$this->halt($msg);
    }
    
    /**
     * trim path according to OS (Windows or Unix)
     * 
     * @param string $path 
     * @access protected
     * @return void
     */
    protected function trimPath($path) {
        return str_replace(array('/', '\\', '//', '\\\\'), DIR_SEP, $path);
    }

    /**
     * get the absolute path of this template
     * 
     * @param string $tpl 
     * @access protected
     * @return void
     */
    protected function getTemplatePath($tpl){
			//if(file_exists($tpl)) return $this->trimPath($tpl);//patched by wjc
			$tpl=basename($tpl);
			return $this->trimPath($this->_options['template_dir'].DIR_SEP.$tpl);
    }

    /**
     * get the absolute path of cache file for the given template
     * 
     * @param string $tpl 
     * @access protected
     * @return void
     */
    protected function getCachePath($tpl) {
        $cache_file = $tpl.'.php';
				$cache_file="cache_".basename($cache_file);//patch by wjc
        return $this->trimPath($this->_options['cache_dir'].DIR_SEP.$cache_file);
    }     

    /**
     * check cached content for the given template whether to recompile or not
     * 
     * @param string $tpl 
     * @param string $md5_data 
     * @param integer $expire_time 
     * @access public
     * @return void
     */
    public function checkCache($tpl, $md5_data, $expire_time) {
        if($this->_options['compile_check'] && md5_file($this->getTemplatePath($tpl)) != $md5_data) {
            $this->saveCache($tpl);
        }
        if($this->_options['cache_lifetime'] != 0 && (time() - $expire_time >= $this->_options['cache_lifetime'])) {
            $this->saveCache($tpl);
        }
    }

    /**
     * parse the template & replace the template tags
     * 
     * @param string $tpl 
     * @access public
     * @return void
     */
    public function parseTemplate($tpl) {
        $tpl_path = $this->getTemplatePath($tpl);

        if(!is_readable($tpl_path)) {
            $this->throwException("Current template file ".basename($tpl,".htm")." not found or have no access!");
					//return "<b>[Failed ".basename($tpl,".htm")."]</b>";
        }

        $template = file_get_contents($tpl_path);

        $template = preg_replace("/\{\*.*?\*\}/ies", "", $template);//TMP should do super parentheses matching?
				
        $template = preg_replace(
        	"/".$this->_options['left_delimiter']."(.+?)".$this->_options['right_delimiter']."/s", 
        	"{\\1}", 
        	$template
        );
        
        $template = preg_replace("/\{lang\s+(.+?)\}/ies", "languagevar('\\1')", $template);
				//$template = preg_replace("/\{(I18N_.+?)\s*\}/ies", "\\1", $template);
        $template = preg_replace("/\{filetime\s+(.+?)\}/is", "<?php echo filetime('\\1'); ?>", $template);

        $template = str_replace("{LF}", "<?=\"\\n\"?>", $template);

        $var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)"."(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
        $template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
        $template = preg_replace("/$var_regexp/es", "addquote('<?=\\1?>')", $template);
        $template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "addquote('<?=\\1?>')", $template);

        $template = preg_replace(
            "/[\n\r\t]*\{inc\s+([a-z0-9_]+)\}[\n\r\t]*/is",
            "\r\n<? include($"."GLOBALS['_tiger_mami']->template()->fetchCache('"."\\1"."')); ?>\r\n",
            $template
        );
        $template = preg_replace(
            "/[\n\r\t]*\{inc\s+(.+?)\}[\n\r\t]*/is",
            "\r\n<? include($"."GLOBALS['_tiger_mami']->template()->fetchCache('\\1')); ?>\r\n",
            $template
        );

        $template = preg_replace(
            "/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/ies", 
            "stripvtags('<? \\1 ?>','')", 
            $template
        );
        $template = preg_replace(
            "/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", 
            "stripvtags('<? echo \\1; ?>','')", 
            $template
        );
        $template = preg_replace(
        	"/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", 
        	"stripvtags('\\1<? } elseif(\\2) { ?>\\3','')",
            $template
        );
        $template = preg_replace(
            "/([\n\r\t]*)\{else\}([\n\r\t]*)/is", 
            "\\1<? } else { ?>\\2", 
            $template
        );

        $nest = 5;
        for ($i = 0; $i < $nest; $i++) {
            $template = preg_replace(
                "/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/ies",
                "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<? } } ?>')",
                $template
            );
            $template = preg_replace(
                "/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/ies",
                "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<? } } ?>')",
                $template
            );
            $template = preg_replace(
                "/([\n\r\t]*)\{if\s+(.+?)\}([\n\r]*)(.+?)([\n\r]*)\{\/if\}([\n\r\t]*)/ies",
                "stripvtags('\\1<? if(\\2) { ?>\\3','\\4\\5<? } ?>\\6')",
                $template
            );
        }

        $template = preg_replace(
            "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/s",
            "<?=\\1?>",
            $template
        );

        $template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);

        $template = preg_replace(
            "/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/e",
            "transamp('\\0')",
            $template
        );
        $template = preg_replace(
            "/\<script[^\>]*?src=\"(.+?)\".*?\>\s*\<\/script\>/ise",
            "stripscriptamp('\\1')",
            $template
        );
        $template = preg_replace(
            "/[\n\r\t]*\{block\s+([a-zA-Z0-9_]+)\}(.+?)\{\/block\}/ies",
            "stripblock('\\1', '\\2')",
            $template
        );

        $md5_data = md5_file($tpl_path);
        $lmt = filemtime($tpl_path);
        $expire_time = time();
        $template = "<? //last-time:$lmt:emit-tsal\r\n"
                  ."if (!class_exists('Tiger_template')) die('Tiger_template Access Denied'); \r\n"
                  ."$"."GLOBALS['_tiger_mami']->template()->checkCache('$tpl', '$md5_data', $expire_time); \r\n"
                  ."//\r\n?>\r\n$template";

        return $template;          
    }
     
    /**
     * test to see if valid cache exists for this template
     * 
     * @param string $tpl 
     * @access public
     * @return void
     */
    public function isCached($tpl) {
    	$cache_path = $this->getCachePath($tpl);
    	if(!file_exists($cache_path)) {
    		return false;
    	} else {
    		return true;
    	}
    }

    public function isChanged($tpl) {
      $tpl_path = $this->getTemplatePath($tpl);
      if(!is_readable($tpl_path)) {
          $this->throwException("Current template file ".basename($tpl,".htm")." not found or have no access!");
        //return "<b>[Failed ".basename($tpl,".htm")."]</b>";
      }

      $lmt = filemtime($tpl_path);
      
      $cache_path = $this->getCachePath($tpl);
      $template = file_get_contents($cache_path);
      //alert($template);
      preg_match('/last-time:(\d+):emit-tsal/', $template, $match);
      //alert($match, 'match');
      $cacheLmt = $match[1];
      //alert($cacheLmt. "   " . $lmt . "   ".($lmt - $cacheLmt), 'lmt');
      if (sizeof($match) == 0 || !$cacheLmt) return true;   //这句是为了覆盖掉以前版本遗留下来的php缓存文件

      //之所以跟 1 比较大小, 是因为怕刚刚好在一秒的尽头做了操作,具体会怎样,没去想,
      //反正1秒对于人类编程的速度来说是绝对没问题的
      if ($lmt - $cacheLmt > 1 || $lmt - $cacheLmt < -1) return true;
      return false;
      
    }
    
    /**
     * executes & returns the cache path for the given template
     * 
     * @param string $tpl 
     * @access public
     * @return void
     */
		public function fetchCache($tpl) {
				//$this->saveCache($tpl);            // tmp by splutter
				//return $this->getCachePath($tpl);  // tmp by splutter@100506
        $cache_path = $this->getCachePath($tpl);
        if(!$this->isCached($tpl)) {
            $this->saveCache($tpl);
        } else {
          if ($this->isChanged($tpl)) {
            $this->saveCache($tpl);
          }
        }
        return $cache_path;
    }    
    
    /**
     * compile & save cached content for the given template
     * 
     * @param string $tpl 
     * @access public
     * @return void
     */
    public function saveCache($tpl) {
    	$template = $this->parseTemplate($tpl);
        $cache_path = $this->getCachePath($tpl);
        file_put_contents($cache_path, $template);        
    }
        
    /**
     * clear cached content for the given template if cache file exists
     * 
     * @param string $tpl 
     * @access public
     * @return void
     */
    public function clearCache($tpl) {
    	if($this->isCached($tpl)) {
    		@unlink($this->getCachePath($tpl));
    	} 
    }
          
    /**
     * clear the entire contents of cache (all templates)
     * 
     * @access public
     * @return void
     */
    public function clearAllCache() {
    	$cache_dir = $this->trimPath($this->_options['cache_dir']);
    	$fs = @scandir($cache_dir);
        foreach($fs as $f) {
            $path = $cache_dir.DIR_SEP.$f;
    		if(is_file($path)) {
    			if(preg_match("/\.php$/", $f)) {
    				@unlink($path);
    			}
    		}
    	}
    }

}
