<?php
//session_start();
require('mysqli_fetch.php');

// fetch_log_login(1, 'user_account@email.com'); : When login succeed
// fetch_log_login(0, 'user_account@email.com', 'thisiswrongpassword'); : When login failed
function fetch_log_login($succeed, $account, $pw=null) {
    if ($succeed) {
        $last_insert_index = __fetch_log('login success', ['account'=>$account]);
    } else {
        $last_insert_index = __fetch_log('login fail', ['account'=>$account, 'password'=>$pw]);
    }
    return $last_insert_index;
}

// fetch_log_changepassword(); : When password has changed
function fetch_log_changepassword($account) {
    $last_insert_index = __fetch_log('change password', ['account'=>$account]);
    return $last_insert_index;
}

// fetch_log_download(1, 54) : When download is finished, add insert_id from the last query
// fetch_log_download(0, '', 'user@account', 'AN00001234', 'TESTSAMPLE1', 'testfile.fastq.gz', ['download' | 'wget' | 'single-use']) : When download starts
function fetch_log_download($finish, $insert_id=null, $userAccount=null, $filename=null, $sample=null, $order=null, $method=null) {
    if ($finish) {
        $last_insert_index = __fetch_log('download finish', ['insert_id'=>$insert_id]);
    } else {
        $last_insert_index = __fetch_log('download', ['account'=>$userAccount, 'filename'=>$filename, 'sample'=>$sample, 'order'=>$order, 'method'=>$method]);
    }
    return $last_insert_index;
} 



/*
Input Arguments:
Required:
    $log_msg: str
        'login success'
        'login fail'
        'change password'
        'download'
        'download finish'
Optional:
    $additional_argument: array() (default=NULL)
        account: To get the user account when login failed
        password: To get the user password when login failed
        ip: To get the user ip when login failed
        order: To get the Order ID when the client starts downloading the file
        sample: To get the sample name when the client starts downloading the file
        filename: To get the file name when the client starts downloading the file
        insert_id: To match the finish time for the DownloadLog finish time
        method: To get the download method when download starts
Return
    Insert ID for the last query

Example
    __fetch_log('login success');
    __fetch_log('login fail', ['account'=>'wrong_id@email.com', 'password'=>'ABCD']);
    __fetch_log('change password');
    __fetch_log('download', ['order'=>'AN01', 'sample'=>'SM01', 'filename'=>'raw.fq.gz', 'method'=>'download']);
    __fetch_log('download', ['order'=>'AN01', 'sample'=>'SM01', 'filename'=>'raw.fq.gz', 'method'=>'wget']);
    __fetch_log('download', ['order'=>'AN01', 'sample'=>'SM01', 'filename'=>'raw.fq.gz', 'method'=>'single-use']);
    __fetch_log('download finish', ['insert_id'=>1]);
*/
function __fetch_log($log_msg, $additional_argument=null){
    if (isset($additional_argument)) {
        extract($additional_argument);
    }
    if(! isset($account)){ 
        $account = $_SESSION['user'];
    }
    $ip = $_SERVER['REMOTE_ADDR']; // IS this IP address correct?
    $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

    // Eatablish db connection
    $connection = __db_fetch();

    // login success
    if ($log_msg === "login success"){
        $sql_query = sprintf("INSERT INTO `User` (`user_account`, `last_login_date`, `num_login_failure` ) VALUES('%s', now(), 0)
        ON DUPLICATE KEY UPDATE `last_login_date`=now(), `num_login_failure`=0;", $connection->real_escape_string($account));
        $sql_query .= sprintf(" INSERT INTO `LoginLog` (`user_id`, `ip`) VALUES((SELECT `id` FROM `User` where `user_account`='%s'), INET_ATON('$ip'));", $connection->real_escape_string($account)); 
    }
    // login fail
    // REVERT to CHAR: select cast(aes_decrypt(user_password, 'keykey') as CHAR) from LoginFailLog;
    elseif ($log_msg === "login fail"){
        $sql_query = sprintf("UPDATE `User` SET `num_login_failure` = `num_login_failure` + 1 WHERE `user_account`='%s';", $connection->real_escape_string($account));
        if (is_null($password)) {
            $sql_query .= sprintf(" INSERT INTO `LoginFailLog` (`user_account`, `user_password`, `ip`) VALUES('%s', NULL, INET_ATON('$ip'));", $connection->real_escape_string($account)); 
        } else {
            $sql_query .= sprintf(" INSERT INTO `LoginFailLog` (`user_account`, `user_password`, `ip`) VALUES('%s', AES_ENCRYPT('%s', 'keykey'), INET_ATON('$ip'));", $connection->real_escape_string($account), $connection->real_escape_string($password)); 
        }
    }
    // change password
    elseif ($log_msg === "change password"){
        $sql_query = sprintf("UPDATE `User` SET `last_password_change_date` = now() WHERE `user_account`='%s';", $connection->real_escape_string($account));
    }
    // download
    elseif ($log_msg === "download"){
        $sql_query = sprintf("INSERT INTO `File` (`user_id`, `order_id`, `sample_name`, `file_name`) VALUES((SELECT `id` FROM `User` WHERE `user_account`='%s'), '$order', '%s', '%s')
        ON DUPLICATE KEY UPDATE `id`=`id`;", $connection->real_escape_string($account), $connection->real_escape_string($sample), $connection->real_escape_string($filename));
        $sql_query .= sprintf(" INSERT INTO `DownloadLog` (`file_id`, `ip`, `method`, `start_timestamp`) VALUES((SELECT `id` FROM `File` WHERE file_name='%s' AND  `sample_name`='%s' AND `order_id`='$order'), INET_ATON('$ip'), '$method', now());", $connection->real_escape_string($filename), $connection->real_escape_string($sample));
    }
    // download finish
    elseif ($log_msg === "download finish"){
        $sql_query = "UPDATE `DownloadLog` SET `finish_timestamp` = now() WHERE `id` = '$insert_id';";
    }
    
    $result_index = execute_query($sql_query, $connection);
    $connection->close();

    return $result_index;
}
?>
