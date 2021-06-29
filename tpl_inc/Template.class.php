<?php
include $_SERVER['DOCUMENT_ROOT']."/tpl_inc/Template_.class.php";
class Template extends Template_
{
	var $compile_check = true;
	var $compile_ext   = 'php';
	var $skin          = 'users';

	var $memory_tmp    = 'memory_tmp';

	var $notice        = false;
	var $path_digest   = false;

	var $prefilter     = '';
	var $postfilter    = '';
	var $permission    = 0777;

	var $safe_mode     = false;
	var $safe_mode_func     = false;
	var $auto_constant = false;

	var $caching       = false;
	var $cache_expire  = 3600;

    function __construct()
    {
		global $now_site_mode, $user_agent;
		if($now_site_mode != "admin"){
			$this->skin = $this->skin.'_'.$user_agent;
		}

		if($now_site_mode == "admin"){
			$this->skin = "admin";
		}

        $this->template_dir=$_SERVER['DOCUMENT_ROOT'].'/_template';
        $this->compile_dir =$_SERVER['DOCUMENT_ROOT'].'/_compile';
        $this->cache_dir   =$_SERVER['DOCUMENT_ROOT'].'/_cache';
    }
}
?>