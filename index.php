<?php require('common.php');?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlspecialchars(_CONFIGS('title'));?></title>
		<link rel="icon" type="image/png" href="favicon.png"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto Sans">
		<link rel="stylesheet" type="text/css" href="index.css"/>
		<script>const urlroot=<?php echo json_encode(_CONFIGS('urlroot'));?>;</script>
		<script src="lib/jquery.js"></script>
		<script src="lib/jquery.cookie.js"></script>
		<script src="lib/vue.js"></script>
		<!-- <script src="lib/d3.js"></script> -->
		<script src="index.js"></script>
	</head>
	<body>
		<img src="src/logo.png"/>
		<h2>Customer Report Drive</h2>
		<div>
			<div><label>Username: <input type="text" autofocus/></label></div>
			<div><label>Password: <input type="password"/></label></div>
			<button>Login</button>
		</div>
	</body>
</html>