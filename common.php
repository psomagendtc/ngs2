<?php
define('dirroot', '/UUU/ngs2');
$__CONFIGS=null;
function _CONFIGS($key=null){
	global $__CONFIGS;
	if($__CONFIGS===null){
		$__CONFIGS=json_decode(file_get_contents(dirroot.'/../ngs2.config.json'), true);
	}
	if($key!==null&&array_key_exists($key, $__CONFIGS)){
		return $__CONFIGS[$key];
	}
	return $__CONFIGS;
}
function error($text='Internal Server Error', $code=500){
    header('HTTP/1.0 '.$code.' '.$text);
    exit();
}
?>