<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: /simple-forum/login.php?page=blog'); }
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
			$error[] = 'Please enter email address.';
		}
		if(!preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
		{
			$error[] = 'Please enter valid email';
		}

		if(!isset($error)){

			//$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);
			$hashedpassword = sha1($password);

			try {
				$stmt = $db->prepare('select id from users where username=:username');
				$stmt->execute(array(':username' => $username));
				$dn = $stmt->rowCount();
				if($dn==0)
				{
					$stmt = $db->prepare('select id from users');
					$stmt->execute();
					$dn2 = $stmt->rowCount();
					$id = $dn2+1;
				}

				//insert into database
				$stmt = $db->prepare('INSERT INTO users (id, username, password, email, avatar, signup_date) VALUES (:id, :username, :password, :email, :avatar, :signup_date)') ;
				$stmt->execute(array(
					':id' => $id,
					':username' => $username,
					':password' => $hashedpassword,
					':email' => $email,
					':avatar' => $avatar,
					':signup_date' => time()
				));

				//redirect to index page
				header('Location: users.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Add User</title>
		<?php include '../../profile/header0.php';
		$topmenu = 4;
		$rightmenu = 0;
		$menupage = 2;
        ?>
        <link rel="stylesheet" href="style.css" />
		<style>
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
		</style>
	</head>
	<body>
		<?php 
		include '../../profile/header1.php';
		include '../../profile/topmenu.php';
		include '../../profile/header2.php';
		include '../../profile/header3.php';
		?>
		<span>
			<div id="wrapper">
				<?php include('menu.php');?>
				<p><a href="users.php">User Admin Index</a></p>
				<h2>Add User</h2>

				<?php
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
					
					<p><label>Avatar</label><br />
					<input type='text' name='avatar' value='<?php if(isset($error)){ echo $_POST['avatar'];}?>'></p>
					
					<p><input type='submit' name='submit' class="btn btn-default" value='Add User'></p>
				</form>
			</div>
		</span>
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>
