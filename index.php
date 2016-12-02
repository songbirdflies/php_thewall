<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8">
	  <title>The Wall Login</title>
	  <link rel="stylesheet" type="text/css" href="style.css">
	  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	</head>

	<body>
		<div id="container">
		<img src="the_wall.jpg" align="center">
		<?php
			if(isset($_SESSION['errors']))
			{
				foreach ($_SESSION['errors'] as $error)
				{
					echo "<p class='error'>" . $error . "</p>";
				}
				unset($_SESSION['errors']);
			}

			if(isset($_SESSION['success_message']))
			{
				echo "<p class='success'>{$_SESSION['success_message']} </p>";
				unset($_SESSION['success_message']);
			}				
		?>
		<h2>Register</h2>
			<form action="process.php" method="post">
				<input type='hidden' name='action' value='register'>
				First Name: <input type="text" name="first_name">
				Last Name: <input type="text" name="last_name">
				Email address: <input type="text" name="email">
				Password: <input type="password" name="password">
				Confirm Password: <input type="password" name="confirm_password">

				<input type="submit" value="register" class="btn btn-info" id="button">
			</form>

			<h2>Login</h2>
			<form action="process.php" method="post">
				<input type='hidden' name='action' value='login'>
				Email address: <input type="text" name="email">
				Password: <input type="password" name="password">

				<input type="submit" value="login" class="btn btn-info" id="button">
			</form>
		</div>
	</body>



</html>