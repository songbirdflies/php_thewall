<?php
session_start();
require('new-connection.php');

	if(isset($_POST['action']) && $_POST['action'] == 'register')
		register_user($_POST); //call to function, use of ACTUAL POST

	if(isset($_POST['action']) && $_POST['action'] == 'login')
		login_user($_POST); //call to function, use of ACTUAL POST
	
	if(isset($_POST['action']) && $_POST['action'] == 'logout') // logging off
		logout();

	if(isset($_POST['action']) & $_POST['action'] == 'post')
		post_message();

	if(isset($_POST['action']) & $_POST['action'] == 'comment')
		make_comment();


	function register_user($post) // parameter called post
	//if this value doesn't work, do this
	{
		$_SESSION['errors'] = array();
		
		if(empty($post['first_name']))
		$_SESSION['errors'][] = "First name cannot be blank.";

		if(empty($post['last_name']))
		$_SESSION['errors'][] = "Last name cannot be blank.";

		if(empty($post['password']))
		$_SESSION['errors'][] = "Password field is required.";

		if($post['password'] !== $post['confirm_password'])
		$_SESSION['errors'][] = "Passwords do not match.";

		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		$_SESSION['errors'][] = "Email must be valid.";

		$password = md5($_POST['password']);

	//--------------------------end of validation --------------------

		if(count($_SESSION['errors']) > 0)
		{
			header('Location: index.php');
			die();
		}
		else 
		{
			$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at)
						VALUES ('{$post['first_name']}', '{$post['last_name']}', '$password', '{$post['email']}',
							NOW(), NOW())";
			
			run_mysql_query($query);
			
			$_SESSION['success_message'] = 'User successfully created! Please proceed to login.';
			header('Location: index.php');
			die();
		}
	}

	function login_user($email, $password)
	{
		$email = $_POST['email'];
		$password = md5($_POST['password']);

		global $connection;
		$esc_email = mysqli_real_escape_string($connection, $email);
		$esc_password = mysqli_real_escape_string($connection, $password);

		if(empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
			$_SESSION['errors'][] = "Email must be valid.";

		/*if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		$_SESSION['errors'][] = "Email must be valid.";	
*/
		if(empty($password))
		{
		$_SESSION['errors'][] = "Password field is required.";
		}
	
		//--------------------------end of validation --------------------

		$query = "SELECT * FROM users WHERE users.password = '$esc_password'
					AND users.email = '$esc_email'";
		$user = fetch($query); // grab user with above credential
		
		if(count($user) > 0)
		{
			$_SESSION['user_id'] = $user[0]['id'];
			$_SESSION['first_name'] = $user[0]['first_name'];
			$_SESSION['email'] = $user[0]['email'];
			$_SESSION['logged_in'] = TRUE;
			$_SESSION['id'] = mysql_insert_id();
			header('Location: wall.php');
			die();
		}
		else
		{
			$_SESSION['errors'][] = "User not found. Please register.";
			header('Location: index.php');
			die();
		}
	}

	
	function post_message()
	{
		if(!empty($_POST['post']))
		{
			$insert_post_query = "INSERT INTO messages (user_id, post, created_at) VALUES ('". $_SESSION['user_id'] ."', '". $_POST['post'] ."', NOW()) ";

			//echo $insert_comment_query;
			$insert_post = run_mysql_query($insert_post_query);

			if($insert_post)
				$_SESSION['notifications'][] = "New post inserted!";
			else
				$_SESSION['errors'][] = "Cannot post right now. Please check database connection.";
		}

		else
			$_SESSION['errors'][] = "Post field must not be empty!";

		header('Location: wall.php');
		exit();


	}

	function make_comment()
	{
		if(!empty($_POST['comment']))
		{
			$insert_comment_query = "INSERT INTO comments (user_id, message_id, comment, created_at) VALUES('". $_SESSION['user_id'] ."', '". $_POST['message_id'] ."', '". $_POST['comment'] ."', NOW()) ";
			$insert_comment = run_mysql_query($insert_comment_query);

			if($insert_comment)
				$_SESSION['notifications'][] = "New comment inserted";
			else
				$_SESSION['errors'][] = "Cannot comment right now. Please check database connection.";
		}
		else
			$_SESSION['errors'][] = "Comment field must not be empty!";

		header('Location: wall.php');
		exit();
	}

	function logout()
	{
		session_destroy();
		header('Location: index.php');
		exit();
	}


?>