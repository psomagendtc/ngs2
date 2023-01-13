<?php require('common.php');?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlspecialchars(_CONFIGS('title'));?></title>
		<meta name="viewport" content="width=device-width"/>
		<meta name="viewport" content="initial-scale=1"/>
		<link rel="icon" type="image/png" href="favicon.png"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto Sans">
		<link rel="stylesheet" type="text/css" href="index.css"/>
		<script>const urlroot=<?php echo json_encode(_CONFIGS('urlroot'));?>;</script>
		<script src="lib/jquery.js"></script>
		<script src="lib/jquery.cookie.js"></script>
		<script src="lib/vue.js"></script>
		<!-- <script src="lib/d3.js"></script> -->
		<script src="com/mainframe.js"></script>
		<script>const __coms=<?php $__coms=explode(',', 'login,list,reset');echo json_encode($__coms);?>, __gets=<?php echo json_encode($_GET);?>;</script>
		<?php foreach($__coms as $com){?><script src="com/<?php echo $com;?>.js"></script>
		<?php }?>
		<script src="index.js"></script>
	</head>
	<body>
		<div id="view">
			<mainframe></mainframe>
		</div>
		<div style="position:absolute;right:20px;bottom:10px;">
			<a href="statement/termsandconditions.php" target="_blank" style="color:white;text-decoration:none;">Terms of Use</a>
			<span style="padding:0 5px;"></span>
			<a href="statement/privacypolicy.php" target="_blank" style="color:white;text-decoration:none;">Privacy Policy</a>
		</div>
		<div id="mask"></div>
	</body>
</html>