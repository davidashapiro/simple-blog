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
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Edit Comment</title>
		<?php 
		include '../../profile/header0.php';
		include '../../profile/header01.php';
		$topmenu = 4;
		$rightmenu = 0;
		$menupage = 3;
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
				<p><a href="./comments.php">Blog Admin Index</a></p>
				<h2>Edit Post</h2>
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
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>	
