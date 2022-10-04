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
		<script>const __coms=<?php $__coms=explode(',', 'login,list,report');echo json_encode($__coms);?>;</script>
		<?php foreach($__coms as $com){?><script src="com/<?php echo $com;?>.js"></script>
		<?php }?>
		<script src="index.js"></script>
	</head>
	<body>
		<div id="view">
			<mainframe></mainframe>
		</div>
		<div id="mask"></div>
	</body>
</html>
