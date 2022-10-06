<?php
session_start();
$project_id=$input['project_id'];
function linkid(){
    return bin2hex(random_bytes(32));
}
if(isset($_SESSION['user'])){
    $userdataroot=_CONFIGS('dataroot').'/'.$_SESSION['user'].'/'.$project_id;
    foreach(glob("{$userdataroot}/*/*") as $filename){
        $sample_name=basename(dirname($filename));
        $file_name=basename($filename);
        $link=linkid();
        symlink($filename, _CONFIGS('linkroot').'/'.$link);
        array_push($output, "wget '"._CONFIGS('urlroot').'/download?id='.urlencode($link)."' -O '{$sample_name}_{$file_name}'");
    }
}else{
    error('Inappropriate Attempt', 403);
}
?>