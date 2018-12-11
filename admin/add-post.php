<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: /simple-forum/login.php?page=blog'); }
	//if form has been submitted process it
	if(isset($_POST['submit'])){
	
		$_POST = array_map( 'stripslashes', $_POST );
	
		//collect form data
		extract($_POST);
	
		//very basic validation
		if($postTitle ==''){
			$error[] = 'Please enter the title.';
		}
	
		if($postDesc ==''){
			$error[] = 'Please enter the description.';
		}
	
		if($postCont ==''){
			$error[] = 'Please enter the content.';
		}
	
		$postOwner = $_SESSION['username'];
		if ($postOwner == '') {
			$error[] = 'username is empty';
		}
		
		if(!isset($error)){

			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO blog_posts (postTitle,postDesc,postCont,postDate,postOwner) VALUES (:postTitle, :postDesc, :postCont, :postDate, :postOwner)') ;
				$stmt->execute(array(
					':postTitle' => $postTitle,
					':postDesc' => $postDesc,
					':postCont' => $postCont,
					':postDate' => date('Y-m-d H:i:s'),
					':postOwner' => $postOwner
				));

				//redirect to index page
				header('Location: index.php?action=added');
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
		<title>Admin - Add Post</title>
		<?php 
		include '../../profile/header0.php';
		include '../../profile/header01.php';
		$topmenu = 4;
		$rightmenu = 0;
		$menupage = 1;
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
				<?php include 'menu.php'; ?>
				<p><a href="./">Blog Admin Page</a></p>
				<h2>Add Post</h2>

	<?php
	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>
				<form action='' method='post'>
					<p><label>Title</label><br />
					<input type='text' name='postTitle' value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>
			
					<p><label>Description</label><br />
					<textarea name='postDesc' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postDesc'];}?></textarea></p>
			
					<p><label>Content</label><br />
					<textarea name='postCont' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['postCont'];}?></textarea></p>
			
					<p><input type='submit' name='submit' class="btn btn-default" value='Submit'></p>
				</form>
			</div>
		</span>
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>
