<?php 
require('../common.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlspecialchars(_CONFIGS('title'));?></title>
		<meta name="viewport" content="width=device-width"/>
		<meta name="viewport" content="initial-scale=1"/>
		<link rel="icon" type="image/png" href="../favicon.png"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto Sans">
		<link rel="stylesheet" type="text/css" href="index.css"/>
		<script>const urlroot=<?php echo json_encode(_CONFIGS('urlroot'));?>;</script>
		<script src="../lib/jquery.js"></script>
		<script src="../lib/jquery.cookie.js"></script>
		<script src="../lib/vue.js"></script>	
		<script src="../com/mainframe.js"></script>
		<!-- <script src="table.js"></script> -->
		<script src="../index.js"></script>
	</head>
	<body>
		<div id="table"></div>
		<script src="table.js"></script>
	</body>
</html>