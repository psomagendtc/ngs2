<?php
session_start();
require('../common.php');
if(isset($_GET['project'])&&isset($_GET['sample'])&&isset($_GET['file'])){
    $project=$_GET['project'];
    $sample=$_GET['sample'];
    $file=$_GET['file'];
    if(isset($_SESSION['user'])){
        $userdataroot=_CONFIGS('dataroot').'/'.$_SESSION['user'];
        $filename="{$userdataroot}/{$project}/{$sample}/{$file}";
    }else{
        error('Inappropriate Attempt', 403);
    }
}else if(isset($_GET['id'])){
    $id=explode('.', $_GET['id'])[0];
    $linkfilename=_CONFIGS('linkroot').'/'.$id;
    if($filename=realpath($linkfilename)){
        unlink($linkfilename);
    }else{
        error('Inappropriate Attempt', 403);
    }
}

$name=basename($filename);
$size=filesize($filename);
$quoted=sprintf('"%s"', addcslashes(basename($name), '"\\'));
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$quoted);
header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: '.$size);
if($fp=fopen($filename, 'rb')){
    ob_end_clean();
    fpassthru($fp);
    fclose($fp);
}
?>