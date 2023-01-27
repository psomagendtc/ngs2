<?php

require('../common.php');

// Connect to the database
$db = __db_fetch();

// // Check connection
// if (!$db) {
//     die("Connection failed: " . mysqli_connect_error());
// }

$query = "select User.user_account, File.order_id, File.sample_name, File.file_name, DownloadLog.start_timestamp, DownloadLog.finish_timestamp, DownloadLog.method  from File left join DownloadLog on (File.id = DownloadLog.file_id) left join
User on (File.user_id = User.id);";

// Execute the query
$result = mysqli_query($db, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($db));
}

// Fetch the data from the database
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close the connection
mysqli_close($db);

//echo json_encode($data); 
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlspecialchars(_CONFIGS('title'));?></title>
		<meta name="viewport" content="width=device-width"/>
		<meta name="viewport" content="initial-scale=1"/>
		<link rel="icon" type="image/png" href="favicon.png"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto Sans">
		
		<script>const urlroot=<?php echo json_encode(_CONFIGS('urlroot'));?>;</script>
		<script src="/lib/jquery.js"></script>
		<script src="/lib/jquery.cookie.js"></script>
		<script src="/lib/vue.js"></script>
		<!-- <script src="lib/d3.js"></script> -->
		<script src="/com/mainframe.js"></script>
		<?php foreach($__coms as $com){?><script src="/com/<?php echo $com;?>.js"></script>
		<?php }?>
		<script src="test.js"></script>
		<script> 
		  
	
		</script>

	</head>
	<body>
		<div id="view">
			<mainframe></mainframe>
		</div>
		<div id="mask"></div>
	</body>
</html>
