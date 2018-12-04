<?php
//include config
require_once('../includes/config.php');


//check if already logged in
if( $usero->is_logged_in() ){ header('Location: index.php'); } 
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin Login</title>
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
		<style>
			* {box-sizing: border-box;}
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
			form input[type=password],
			form input[type=text]{
				background-color: #eaeaea;
				height: 35px;
				border: 1px solid dodgerblue;
				width:100%;
				padding: 10px;
				margin: 10px;
				margin-left: 0;
				border-radius: 0;
			}
			form input[type=password]:focus,
			form input[type=text]:focus {
				border: 2px solid red;
			}
			/* Style the input container */
			.input-container {
				display: flex;
				width: 100%;
				margin-bottom: 15px;
			}
			
			/* Style the form icons */
			.icon {
				padding: 10px;
				background: dodgerblue;
				color: white;
				min-width: 50px;
				width: 30px;
				text-align: center;
				height: 35px;
				margin: 10px;
				margin-right: 0;
			}
			.leftmargin {
				margin-left: 50px;
			}
			#login {
				background: url(/profile/images/logot1.png); 
				margin-left: 10%;
				margin-right: 10%;
				margin-bottom: 10%;
				margin-top: 20px;
				padding: 25px;
				width: 80%;
			}
		</style>
	</head>
	<body>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part3.js'></script>
		<span>
			<div id="login">
				<?php
			
				//process login form if submitted
				if(isset($_POST['submit'])){
			
					$username = trim($_POST['username']);
					$password = trim($_POST['password']);
					
					if($usero->login($username,$password)){ 
						//logged in return to index page
						header('Location: index.php');
						exit;
					} else {
						$message = '<p class="error">Wrong username or password</p>';
					}
			
				}//end if submit
			
				if(isset($message)){ echo $message; }
				?>
				<form action="" method="post">
					<div class="row form-group">
						<div class="input-container">
							<i class="fa fa-user icon"></i>
							<input type="text" class="form-control" name="username" id="username" placeholder="Username or Email">
						</div>
					</div>
					<div class="row form-group">
						<div class="input-container">
							<i class="fa fa-key icon"></i>
							<input type="password" class="form-control" name="password" id="password" placeholder="Password"><br />
						</div>
					</div>
					<div class="row form-group">
						<input type="submit" class="btn btn-primary leftmargin" name="submit" value="Login">
						<a href="register.php" class="btn btn-primary" style="color:white; ">Register</a>
					</div>
				</form>	
			</div>
		</span>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
