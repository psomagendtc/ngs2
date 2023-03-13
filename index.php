<?php 
// If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on" ) {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    exit;
}
require('common.php');
?>
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
		<div class="__statement" style="position:fixed;left:20px;bottom:10px;color:white;opacity:0.7;">
			<a href="//psomagen.com" style="font-size:20px;margin-right:15px;text-decoration:none;color:white;" target="_blank">Psomagen</a>1330 Piccard Dr Suite 103, Rockville, MD 20850
		</div>
		<div class="__statement" style="position:fixed;right:20px;bottom:10px;opacity:0.7;">
			<a href="statement/termsandconditions.php" target="_blank" style="color:white;text-decoration:none;">Terms of Use</a>
			<span style="padding:0 5px;"></span>
			<a href="statement/privacypolicy.php" target="_blank" style="color:white;text-decoration:none;">Privacy Policy</a>
		</div>
		<script>
		setInterval(()=>{
			if(document.location.hash=="")$(".__statement").show();
			else $(".__statement").hide();
		}, 100);
		</script>
		<div id="notice" style="position:fixed;left:64px;top:64px;font-size:16px;color:black;text-align:center;width:440px;padding:20px;background-color:#ffff00ef;border:3px solid white;outline:10px solid #ffff0099;">
			<div style="font-size:24px;font-weight:bold;">Important Notice</div>
			<div style="font-size:18px;font-weight:bold;margin:20px 0;">Mar 13, 2023<br/>Scheduled maintenance</div>
			<div>Dear valued users,<br><br>We would like to inform you that a server maintenance is scheduled from <u style="color:blue;font-weight:bold;">15:00 to 19:00 on March 13, 2023 (EST)</u>.<br/>As a result, you may encounter some difficulties in accessing the website functionalities such as "Login" and "Download" during this period.</div>
			<div style="margin-top:10px;">We apologize for any inconvenience caused and appreciate your understanding.</div>
			<div style="margin-top:10px;font-size:18px;">Psomagen, Inc.</div>
			<div style="display:inline-block;color:blue;cursor:pointer;margin-top:20px;" onclick="document.getElementById('notice').remove()">[Close]</div>
		</div>
		<div id="mask"></div>
	</body>
</html>