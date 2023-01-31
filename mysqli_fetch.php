<?php
/*
return -2: connection is not established
return -1: Query is not fetched
return $insert_id: Query is fecthed
*/

// function execute_query($sql_query, $connection){    
//     $result = $connection->multi_query($sql_query);
    
//     // Store insert_id for the last query
//     $last_id = $connection->insert_id;
//     do {
//         $connection->next_result();
//         $last_id = $connection->insert_id;
//     } while($connection->next_result() && $connection->more_results());

//     if (is_object($result)) {
//         $result->close();
//         return -1;
//     }
    
//     return $last_id;
// }

// function __db_fetch($sql_query){
//     $db = _CONFIGS('db');
//     $dbuser = _CONFIGS('dbuser');
//     $dbpw = _CONFIGS('dbpw');
//     $dbhostname = _CONFIGS('dbhostname');

//     $connection = new mysqli($dbhostname, $dbuser, $dbpw, $db);
//     if ($connection->connect_errno){
//         return -2;
//     }
//     mysqli_set_charset($connection, "utf8mb4");

//     $insert_id = execute_query($sql_query, $connection);    

//     $connection->close();
//     return $insert_id;
// }

//////////////////////////////////////////////////
function execute_select_query_for_log($sql_query, $connection) {
    $result = $connection->query($sql_query);
    $log_data = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        if (count($row) > 1) {
            unset($row['finish_timestamp']);
            $fileNameLower = strtolower($row['file_name']);
            $splitWords = explode('.', $fileNameLower);
            $fileLen = count($splitWords);
            $fileType = null;
            $fileTypePos = 4;

            if ($splitWords[$fileLen-1] === 'sqs') {
                $fileType =  'SQS';
            } else if ($splitWords[$fileLen-1] === 'vcf') {
                $fileType = 'VCF';
            } else if (str_contains($fileNameLower, 'fastqc')) {
            // } else if (splitWords.includes('fastqc', fileLen-1) || splitWords.includes('fastqc', fileLen-2)) {
                $fileType = 'FASTQC';
            } else if ($splitWords[$fileLen-1] === 'fastq' || $splitWords[$fileLen-2] === 'fastq') {
                $fileType = 'FASTQ';
            } else if (str_contains($fileNameLower, 'md5')) {
            // } else if (splitWords.includes('md5', fileLen-1) || splitWords.includes('md5', fileLen-2)) {
                $fileType = 'MD5';
            } else if (str_contains($fileNameLower, 'bam')) {
                $fileType = 'BAM';
            } else if (str_contains($fileNameLower, 'stat') || str_contains($fileNameLower, 'anno')) {
                $fileType = 'STAT';
            } else if ($splitWords[$fileLen-1] === 'tar') {
                $fileType = 'TAR';
            } else if ($splitWords[$fileLen-1] === 'zip') {
                $fileType = 'ZIP';
            } else if ($splitWords[$fileLen-1] === 'txt') {
                $fileType = 'TXT';
            } else {
                $fileType = 'ETC';
            }
            $row = array_merge(array_slice($row, 0, $fileTypePos), array('file_type' => $fileType), array_slice($row, $fileTypePos));
        } 
        $log_data[] = $row;
    }
    return $log_data;
}

function execute_query($sql_query, $connection){    
    $result = $connection->multi_query($sql_query);
    
    // Store insert_id for the last query
    $last_id = $connection->insert_id;
    do {
        $connection->next_result();
        $last_id = $connection->insert_id;
    } while($connection->next_result() && $connection->more_results());

    if (is_object($result)) {
        $result->close();
        return -1;
    }
    
    return $last_id;
}

function __db_fetch(){
    $db = _CONFIGS('db');
    $dbuser = _CONFIGS('dbuser');
    $dbpw = _CONFIGS('dbpw');
    $dbhostname = _CONFIGS('dbhostname');

    $connection = new mysqli($dbhostname, $dbuser, $dbpw, $db);
    if ($connection->connect_errno){
        return -2;
    }
    mysqli_set_charset($connection, "utf8");

    $insert_id = execute_query($sql_query, $connection);    

    // $connection->close();
    return $connection;
}

?>
