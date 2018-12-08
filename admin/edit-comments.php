<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: /simple-forum/login.php?page=blog'); }

$row = NULL;
$username = null;

try {

	$stmt = $db->prepare('SELECT commentID,commentOwner,commentDate,commentTitle,commentCont, ownerID,parentID  FROM blog_comments WHERE commentID = :commentID') ;
	$stmt->execute(array(':commentID' => $_GET['id']));
	$row = $stmt->fetch(); 
	
	$stmt = $db->prepare('SELECT username FROM blog_members WHERE memberID = :memberID');
	$stmt->execute(array(':memberID' => $row['ownerID']));
	$username = $stmt->fetch();
	
	if ($username['username'] != $_SESSION['username']) 
	{
		header('Location: comments.php?action='.urlencode('User is not an owner of this comment and can not edit it'));
		exit;
	}

} catch(PDOException $e) {
    echo $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Edit Comment</title>
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
		<?php $menupage = 3; ?>
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
				<p><a href="./comments.php">Blog Admin Index</a></p>
				<h2>Edit Post</h2>

	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		$_POST = array_map( 'stripslashes', $_POST );

		//collect form data
		extract($_POST);
		
		if ($username['username'] == $_SESSION['username']) 
		{
			if($commentCont ==''){
				$error[] = 'Please enter the content.';
			}
			
			if(!isset($error)){
	
				try {
	
					//insert into database
					$stmt = $db->prepare('UPDATE blog_comments SET commentCont = :commentCont WHERE commentID = :commentID') ;
					$stmt->execute(array(
						':commentCont' => $commentCont,
						':commentID' => $commentID
					));
	
					//redirect to index page
					header('Location: comments.php?action=updated');
					exit;
	
				} catch(PDOException $e) {
				    echo $e->getMessage();
				}
			}
			header('Location: comments.php?acrion=error');
		}

	}

	?>


	<?php
	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class"error">'.$error.'</p><br />';
		}
	}
	?>

				<form action='' method='post'>
					<input type='hidden' name='commentID' value='<?php echo $row['commentID'];?>'>
					<input type="hidden" name="parentID" value="<?php echo $row['parentID'];?>" />
					<input type="hidden" name="ownerID" value="<?php echo $row['ownerID'];?>" />
					<input type="hidden" name="commentTitle" value="<?php echo $row['commentTitle'];?>" />
					<input type="hidden" name="commentOwner" value="<?php echo $row['commentOwner'];?>" />
					<input type="hidden" name="commentDate" value="<?php echo $row['commentDate'];?>" />
					
					<p><label>Comment</label><br />
					<textarea name='commentCont' cols='60' rows='10'><?php echo $row['commentCont'];?></textarea></p>
			
					<p><input type='submit' name='submit' class="btn btn-default" value='Update'></p>
				</form>
			</div>
		</span>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>	
