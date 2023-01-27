<?php
session_start();
require('../common.php');

$query = '';

if (isset($_GET['page'])&&isset($_GET['start'])&&isset($_GET['end'])&&isset($_GET['type'])&&isset($_GET['field'])) {
    
    $page = $_GET['page'];
    $startTimestamp = $_GET['start'];
    $endTimestamp = $_GET['end'];
    $searchType = $_GET['type'];
    $searchField = $_GET['field'];
    $searchField = "%".$searchField."%";

    $searchDict = array(
        "Order" => "order_id",
        "Sample" => "sample_name",
        "File" => "file_name",
        "User" => "user_account"
    );
    if ($page > 0) {
        $recordPerPage = 28;
        $offsetValue = ($page - 1) * $recordPerPage;
    }

    // Count total rows when endTimestamp is not provided
    if ($page == 0 && $endTimestamp === '') {
        $query = sprintf("SELECT COUNT(`User`.`user_account`) AS count FROM `File` 
        LEFT JOIN `DownloadLog` ON (`File`.`id` = `DownloadLog`.`file_id`) 
        LEFT JOIN `User` ON (`File`.`user_id` = `User`.`id`) 
        WHERE `%s` LIKE '%s' AND `start_timestamp` >= '%s' AND `finish_timestamp` IS NULL;", 
        $searchDict[$searchType], $searchField, $startTimestamp,);
    // Count total rows when endTimestamp is provided
    } elseif ($page == 0) {
        $query = sprintf("SELECT COUNT(`User`.`user_account`) AS count FROM `File` 
        LEFT JOIN `DownloadLog` ON (`File`.`id` = `DownloadLog`.`file_id`) 
        LEFT JOIN `User` ON (`File`.`user_id` = `User`.`id`) 
        WHERE `%s` LIKE '%s' AND `start_timestamp` >= '%s' AND `start_timestamp` <= '%s 23:59:59';", 
        $searchDict[$searchType], $searchField, $startTimestamp, $endTimestamp);
    // Get ${page}-th row when endTimestamp is not provided
    } elseif ($endTimestamp === '') {
        $query = sprintf("SELECT `User`.`user_account`, `File`.`order_id`, `File`.`sample_name`, 
        `File`.`file_name`, `DownloadLog`.`start_timestamp`, `DownloadLog`.`finish_timestamp` FROM `File` 
        LEFT JOIN `DownloadLog` ON (`File`.`id` = `DownloadLog`.`file_id`) 
        LEFT JOIN `User` ON (`File`.`user_id` = `User`.`id`) 
        WHERE `%s` LIKE '%s' AND `start_timestamp` >= '%s' AND `finish_timestamp` IS NULL ORDER BY `start_timestamp` DESC
        LIMIT %s OFFSET %s;", $searchDict[$searchType], $searchField, $startTimestamp, $recordPerPage, $offsetValue);
    // Get ${page}-th row when endTimestamp is provided
    } else {
        $query = sprintf("SELECT `User`.`user_account`, `File`.`order_id`, `File`.`sample_name`, 
        `File`.`file_name`, `DownloadLog`.`start_timestamp`, `DownloadLog`.`finish_timestamp` FROM `File` 
        LEFT JOIN `DownloadLog` ON (`File`.`id` = `DownloadLog`.`file_id`) 
        LEFT JOIN `User` ON (`File`.`user_id` = `User`.`id`) 
        WHERE `%s` LIKE '%s' AND `start_timestamp` >= '%s' AND `start_timestamp` <= '%s 23:59:59' ORDER BY `start_timestamp` DESC 
        LIMIT %s OFFSET %s;", $searchDict[$searchType], $searchField, $startTimestamp, $endTimestamp, $recordPerPage, $offsetValue);
    }
} else {
    error('Inappropriate Attempt', 403);
}

// Execute query
$db = __db_fetch();
$logData = execute_select_query($query, $db);
echo json_encode($logData);
mysqli_close($db);