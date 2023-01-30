<?php
session_start();
require('../common.php');
define('MC_log', 1);

set_time_limit(0);
ini_set('max_execution_time', 0);
$linkfilename=null;
$userAccount=null;
if(isset($_GET['project'])&&isset($_GET['sample'])&&isset($_GET['file'])){
//Internal mode: unlimited download allowed
    $project=$_GET['project'];
    $sample=$_GET['sample'];
    $file=$_GET['file'];
    if(isset($_SESSION['user'])){
        $userdataroot=_CONFIGS('dataroot').'/'.$_SESSION['user'];
        $filename=realpath("{$userdataroot}/{$project}/{$sample}/{$file}");
        if(MC_log){//MC
            $method = 'download';
            $userAccount=$_SESSION['user'];
        }
    }else{
        error('Inappropriate Attempt', 403);
    }
}else if(isset($_GET['id'])){
//External mode: limited download
    $id=explode('.', $_GET['id'])[0];
    $linkfilename=_CONFIGS('linkroot').'/'.$id;
    if($filename=realpath($linkfilename)){
        if(MC_log){//MC
            $userAccountRaw=readlink($linkfilename);
            $userAccount=explode("/", $userAccountRaw);
            $userAccount=array_reverse($userAccount)[3];
            fetch_log_login(True, $userAccountRaw);
            if (isset($_GET['method'])) {
                $method = 'wget';
            } else $method = 'single-use';
        }
    }else{
        error('Inappropriate Attempt', 403);
    }
}
$name=basename($filename);
$size=filesize($filename);
$quoted=sprintf('"%s"', addcslashes(basename($name), '"\\'));
header('X-Sendfile: '.$filename);
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$quoted);
/*header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: public');
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$quoted);
header('Content-Transfer-Encoding: chunked');
header('Content-Length: '.$size);*/
if(MC_log){//MC
    $info_list = explode("/", $filename);
    $inserted_id = fetch_log_download(False, '', $userAccount, array_pop($info_list), array_pop($info_list), array_pop($info_list), $method);
}
/*ob_clean();   
ob_end_flush();
@readfile($filename);*/
if(MC_log){//MC
    fetch_log_download(True, $inserted_id);
}
if($linkfilename!==null){
    unlink($linkfilename);
}
exit;
?>