<?php
session_start();
require_once("new-connection.php");

	if(!isset($_SESSION['user_id']) OR $_SESSION['logged_in'] != TRUE)
	{
		$_SESSION['errors'][] = "Cannot log in. Please check database connection.";
		header("Location: index.php");
		$_SESSION = array();
	}

	$posts_query = "SELECT first_name, last_name, email, /* users table */
							post, messages.created_at, messages.user_id as user_id, messages.id as message_id /* posts table */
					FROM messages
					LEFT JOIN users 
					ON users.id = messages.user_id 
					ORDER BY messages.created_at DESC";

	$posts = fetch($posts_query);

	$comments_query = "SELECT first_name, last_name, email, /* users table */
								comment, comments.created_at, comments.user_id as user_id, comments.id as comment_id, comments.message_id as message_id /* comments table */
						FROM comments 
						LEFT JOIN users ON users.id = comments.user_id 
						ORDER BY comments.created_at DESC";

	$comments = fetch($comments_query);
?>

<html>
	<head>
		<title>The Wall</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	  	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	</head>

	<body>
		<div id="wall_container">
			<form action="process.php" method="post">
				<input type="hidden" name="action" value="logout" />
				<input type="submit" value="logout" class="btn btn-primary" id="button">
			</form>
			<h2><?php echo "Welcome, {$_SESSION['first_name']}!"; ?></h2>
			<h3><?php echo "{$_SESSION['email']}"; ?></h3>

			<form action="process.php" method="post">
				<input type="hidden" name="action" value="post" />
				<textarea name="post" id="post" cols="60" rows="5" placeholder="What is on your mind?" class="area"></textarea> <br />
				<input type="submit" value="post"  class="btn btn-info" id="button">
			</form>
		<?php 	if(isset($_SESSION['notifications'])) // notification if posted correctly
		{
			foreach($_SESSION['notifications'] as $notification)
			{
				echo "<p style='color: green;'> $notification </p>";
			}
		}
		
		if(isset($_SESSION['errors'])) // error if errors
		{
			foreach($_SESSION['errors'] as $error)
			{
				echo "<p style='color: red;'> $error </p>";
			}
		}
		$_SESSION['notifications'] = array();
		$_SESSION['errors'] = array();
		?>
<?php 	if(isset($posts) && !empty($posts)) // Post Message
		{ ?>
			<ol>
<?php		foreach($posts as $post)
			{ ?>
				<li><p><?php echo $post['post']; ?></p>
					<small>by <?php echo $post['first_name']; ?> | <?php echo $post['created_at']; ?></small> 
				<ul>					
<?php			foreach($comments as $comment)
				{ 
					if($post['message_id'] == $comment['message_id'])
					{ ?>
					<li>
						<p><?php echo $comment['comment']; ?></p>
						<small>by <?php echo $comment['first_name'] ?> | <?php echo $comment['created_at']; ?></small>
					</li>
<?php 				}
				} ?>
					<li>
						<form action="process.php" method="post">
							<input type="hidden" name="action" value="comment" />
							<input type="hidden" name="message_id" value="<?php echo $post['message_id']; ?>" />
							<input type="text" name="comment" placeholder="comment..." />
							<button type="submit" class="btn btn-info" id="button">Comment</button>
						</form>
					</li>
				</ul>
<?php		} ?>
			</ol>
<?php	} ?>
		</div>
	</body>
</html>