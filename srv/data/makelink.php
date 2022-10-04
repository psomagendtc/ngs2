<?php
session_start();
function linkid(){
    return bin2hex(random_bytes(32));
}
if(isset($input['project'])&&isset($input['sample'])&&isset($input['file'])){
    $project=$input['project'];
    $sample=$input['sample'];
    $file=$input['file'];
    if(isset($_SESSION['user'])){
        $userdataroot=_CONFIGS('dataroot').'/'.$_SESSION['user'];
        $filename="{$userdataroot}/{$project}/{$sample}/{$file}";
        $link=linkid();
        symlink($filename, _CONFIGS('linkroot').'/'.$link);
        $output=['link'=>$link];
    }else{
        error('Inappropriate Attempt', 403);
    }
}
?>