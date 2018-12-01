<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
//if(!$usero->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Add User</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.css" />
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.js"></script>
    	<link href='/profile/css/styles.css' rel='stylesheet' type='text/css'>
    	<script src="/profile/scripts/scrolltop.js" type="text/javascript"></script>
		<!-- //////// Favicon ////////  -->
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/favicon.ico" type="image/x-icon">

		<script language='Javascript' type='text/javascript'>
			var topmenu = 4;
			var rightmenu = 0;
		</script>
		<?php $menupage = 2; ?>
		<style>
			#adminmenu {
				padding-left: 0;
			}

			#adminmenu li {
				float: left;
				list-style: none;
				margin-right: 20px;
			}
			form input[type=password],
			form input[type=text]{
				background-color: #eaeaea;
				margin-bottom: 10px;
				height: 30px;
				border: none;
				width:100%;
			}
			form input[type=password]:focus,
			form input[type=text]:focus {
				border: 2px solid #ff0000;
			}
			.error {
				padding: 0.75em;
				margin: 0.75em;
				border: 1px solid #990000;
				max-width: 400px;
				color: #990000;
				background-color: #FDF0EB;
				-moz-border-radius: 0.5em;
				-webkit-border-radius: 0.5em;
			}
		</style>
	</head>
	<body>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part3.js'></script>
		<span>
			<div id="wrapper">
				<h2>Register User</h2>

	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($username ==''){
			$error[] = 'Please enter the username.';
		}

		if($password ==''){
			$error[] = 'Please enter the password.';
		}

		if($passwordConfirm ==''){
			$error[] = 'Please confirm the password.';
		}

		if($password != $passwordConfirm){
			$error[] = 'Passwords do not match.';
		}

		if($email ==''){
			$error[] = 'Please enter the email address.';
		}
		
		if ($userDob == '') {
			$error[] = 'please enter the DOB fo the user';
		}

		if(!isset($error)){

			$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO blog_members (username,password,email,userDob) VALUES (:username, :password, :email, :userDob)') ;
				$stmt->execute(array(
					':username' => $username,
					':password' => $hashedpassword,
					':email' => $email,
					':userDob' => $userDob
				));

				//redirect to index page
				header('Location: users.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

				<form action='' method='post'>
					<p><label>Username</label><br />
					<input type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>
			
					<p><label>Password</label><br />
					<input type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>
			
					<p><label>Confirm Password</label><br />
					<input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>
			
					<p><label>Email</label><br />
					<input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>
					
					<p><label>Date of birth</label><br />
					<input type='text' name='userDob' value='<?php if(isset($error)){ echo $_POST['userDob'];}?>'></p>
					
					<p><input type='submit' name='submit' class="btn btn-primary" style="color:white;" value='Register'>
					<a href="login.php" class="btn btn-primary" style="color:white;">Cancel</a></p>
				</form>
			</div>
		</span>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
