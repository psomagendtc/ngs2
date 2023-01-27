<?php
// 120922 MC Added: added insert ftp_log login_log
session_start();
require('../common.php');
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
        fetch_log_login(true, $user); // MC
        return true;
    }else {
        fetch_log_login(false, $user, $password); // MC
        error('invalid id/pw', 403);
    }
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