<?php
$u=$input['u'];
$id=base64_decode($u);
$token=$input['token'];
$pw=$input['pw'];
$password_filename=userdir($id).'/password';
if(file_exists($password_filename)){
    $passwords=explode("\n", file_get_contents($password_filename));
    if($token==md5($passwords[0]).md5($passwords[1])){
        $command="python ".dirroot."/__u__/make_password.py '".str_replace("'", "'\\''", $pw)."' > '"._CONFIGS('dataroot')."/".$id."/password'";
        $command_filename=tempnam(_CONFIGS('dataroot').'/ngs2_password_reset_daemon_job', 'command_');
        unlink($command_filename);
        $command_filename.='.sh';
        file_put_contents($command_filename, $command);
        if (is_bool($input['reset']) && $input['reset']) { // MC
            fetch_log_changepassword($id);
        }
        $output=true;
    }
}
?>
