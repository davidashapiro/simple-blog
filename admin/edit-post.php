<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: /simple-forum/login.php?page=blog'); }
	//if form has been submitted process it
	if(isset($_POST['submit'])){

		$_POST = array_map( 'stripslashes', $_POST );

		//collect form data
		extract($_POST);

		if ($postOwner == $_SESSION['username']) {
			//very basic validation
			if($postID ==''){
				$error[] = 'This post is missing a valid id!.';
			}
	
			if($postTitle ==''){
				$error[] = 'Please enter the title.';
			}
	
			if($postDesc ==''){
				$error[] = 'Please enter the description.';
			}
	
			if($postCont ==''){
				$error[] = 'Please enter the content.';
			}
			
			/*if ($postOwner != $_SESSION['username']) {
				header('Location: index.php?action=error');
				exit();
			}*/
			
			if(!isset($error)){
	
				try {
	
					//insert into database
					$stmt = $db->prepare('UPDATE blog_posts SET postTitle = :postTitle, postDesc = :postDesc, postCont = :postCont, postOwner = :postOwner WHERE postID = :postID') ;
					$stmt->execute(array(
						':postTitle' => $postTitle,
						':postDesc' => $postDesc,
						':postCont' => $postCont,
						':postOwner' => $postOwner,
						':postID' => $postID
					));
	
					//redirect to index page
					header('Location: index.php?action=updated');
					exit;
	
				} catch(PDOException $e) {
				    echo $e->getMessage();
				}
			}
			header('Location: index.php?acrion='.urlencode('There are errors and record can not be updated'));
		}
	}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Edit Post</title>
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
				<?php include('menu.php');?>
				<p><a href="./">Blog Admin Index</a></p>
				<h2>Edit Post</h2>
				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo $error.'<br />';
					}
				}
				try 
				{
					$stmt = $db->prepare('SELECT postID, postTitle, postDesc, postCont, postOwner FROM blog_posts WHERE postID = :postID') ;
					$stmt->execute(array(':postID' => $_GET['id']));
					$row = $stmt->fetch(); 
				} 
				catch(PDOException $e) {
				    echo $e->getMessage();
				}
			
				?>

				<form action='' method='post'>
					<input type='hidden' name='postID' value='<?php echo $row['postID'];?>'>
					<input type="hidden" name="postOwner" value="<?php echo $row['postOwner'];?>" />
			
					<p><label>Title</label><br />
					<input type='text' name='postTitle' value='<?php echo $row['postTitle'];?>'></p>
			
					<p><label>Description</label><br />
					<textarea name='postDesc' cols='60' rows='10'><?php echo $row['postDesc'];?></textarea></p>
			
					<p><label>Content</label><br />
					<textarea name='postCont' cols='60' rows='10'><?php echo $row['postCont'];?></textarea></p>
			
					<p><input type='submit' name='submit' class="btn btn-default" value='Update'></p>
				</form>
			</div>
		</span>
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>	
