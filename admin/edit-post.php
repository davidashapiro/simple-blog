<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Edit Post</title>
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
		<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
		<script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          });
		</script>
		<?php $menupage = 1; ?>
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
				<?php include('menu.php');?>
				<p><a href="./">Blog Admin Index</a></p>
				<h2>Edit Post</h2>

	<?php

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


	<?php
	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}

		try {

			$stmt = $db->prepare('SELECT postID, postTitle, postDesc, postCont, postOwner FROM blog_posts WHERE postID = :postID') ;
			$stmt->execute(array(':postID' => $_GET['id']));
			$row = $stmt->fetch(); 

		} catch(PDOException $e) {
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
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>	
