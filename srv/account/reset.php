<?php
$id=$input['id'];
$password_filename=userdir($id).'/password';
if(file_exists($password_filename)){
    $passwords=explode("\n", file_get_contents($password_filename));
    $token=md5($passwords[0]).md5($passwords[1]);
    $command=[];
    foreach(['python', dirroot.'/__u__/reset_password.py', _CONFIGS('gmail_id'), _CONFIGS('gmail_pw'), $id, _CONFIGS('urlroot').'?u='.base64_encode($id).'&i='.$token.'#reset'] as $chunk){
        array_push($command, "'".$chunk."'");
    }
    $command=implode(' ', $command);
    system($command);
}
?>