<?php
session_start();
require('../common.php');
function error($text='Internal Server Error', $code=500){
    header('HTTP/1.0 '.$code.' '.$text);
}
function userdir($user=null){
    if($user===null){
        if(isset($_SESSION['user'])){
            $user=$_SESSION['user'];
        }else{
            error("permission denied", 403);
        }
    }
    return _CONFIGS('dataroot').'/'.$user;
}
function login($user, $password){
    $userdir=userdir($user);
    $password_filename="{$userdir}/password";
    if($fp=fopen($password_filename, 'r')){
        $salt=trim(fgets($fp));
        $hash=trim(fgets($fp));
        fclose($fp);
    }
    if(strlen($salt)==32&&strlen($hash)==128&&hash('sha512', $password.$salt)==$hash){
        $_SESSION['user']=$user;
        return true;
    }else error('invalid id/pw', 403);
}
function logout(){
    unset($_SESSION['user']);
}
function _P(){
    if(isset($_SESSION['user']))return true;
    else error('invalid access', 403);
}
$input=json_decode($_POST['data'], true);
$method=$_POST['method'];
$output=[];
require("{$method}.php");
if($output!==null)echo json_encode($output);
?>