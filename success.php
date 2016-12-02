<?php
session_start();
	
?>
<html>
	<head>
		<title>Login Successful</title>
	</head>

	<body>
		<p><?php echo "Howdy {$_SESSION['first_name']}!"; ?></p>
		<a href="process.php">Log out</a>
	</bod>
</html>