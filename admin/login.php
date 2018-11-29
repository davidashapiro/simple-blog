<?php
//include config
require_once('../includes/config.php');


//check if already logged in
if( $user->is_logged_in() ){ header('Location: index.php'); } 
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
					
					if($user->login($username,$password)){ 
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
						<div class="col-md-12">
							<label for="username">Username or Email</label>
							<input type="text" class="form-control" name="username" id="username">
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-12">
							<label for="password">Password</label>
							<input type="password" class="form-control" name="password" id="password"><br />
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-12">
							<input type="submit" class="btn btn-primary" name="submit" value="Login">
						</div>
					</div>
				</form>	
			</div>
		</span>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
