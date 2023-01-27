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
function execute_select_query($sql_query, $connection) {
    $result = $connection->query($sql_query);
    $log_data = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
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
    mysqli_set_charset($connection, "utf8mb4");

    $insert_id = execute_query($sql_query, $connection);    

    // $connection->close();
    return $connection;
}

?>