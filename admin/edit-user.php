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

		if( strlen($password) > 0){

			if($password ==''){
				$error[] = 'Please enter the password.';
			}

			if($passwordConfirm ==''){
				$error[] = 'Please confirm the password.';
			}

			if($password != $passwordConfirm){
				$error[] = 'Passwords do not match.';
			}

		}
		
		if($email ==''){
			$error[] = 'Please enter the email address.';
		}

		if(!isset($error)){

			try {

				if(isset($password)){

					//$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);
					$hashedpassword = sha1($password);

					//update into database
					$stmt = $db->prepare('UPDATE users SET username = :username, password = :password, email = :email, avatar = :avatar WHERE id = :id') ;
					$stmt->execute(array(
						':username' => $username,
						':password' => $hashedpassword,
						':email' => $email,
						':id' => $id,
						':avatar' => $avatar
					));


				} else {

					//update database
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, email = :email, avatar = :avatar WHERE id = :id') ;
					$stmt->execute(array(
						':username' => $username,
						':email' => $email,
						':id' => $id,
						':avatar' => $avatar
					));
				}
				
				//redirect to index page
				header('Location: users.php?action=updated');
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
		<title>Admin - Edit User</title>
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
				<h2>Edit User</h2>
				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo $error.'<br />';
					}
				}
			
				try {
			
					$stmt = $db->prepare('SELECT id, username, email, avatar FROM users WHERE id = :id') ;
					$stmt->execute(array(':id' => $_GET['id']));
					$row = $stmt->fetch(); 
			
				} catch(PDOException $e) {
					    echo $e->getMessage();
				}
				?>

				<form action='' method='post'>
					<input type='hidden' name='id' value='<?php echo $row['id'];?>'>
			
					<p><label>Username</label><br />
					<input type='text' name='username' value='<?php echo $row['username'];?>'></p>
			
					<p><label>Password (only to change)</label><br />
					<input type='password' name='password' value=''></p>
			
					<p><label>Confirm Password</label><br />
					<input type='password' name='passwordConfirm' value=''></p>
			
					<p><label>Email</label><br />
					<input type='text' name='email' value='<?php echo $row['email'];?>'></p>
					
					<p><label>Avatar</label><br />
					<input type='text' name='avatar' value='<?php echo $row['avatar'];?>'></p>
			
					<p><input type='submit' name='submit' class="btn btn-default" value='Update User'></p>
				</form>
			</div>
		</span>
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>	
