<?php require('includes/config.php'); 

try {
	
	$stmt = $db->prepare('SELECT postID, postTitle, postCont, postDate, postOwner FROM blog_posts WHERE postID = :postID');
	$stmt->execute(array(':postID' => $_GET['id']));
	$row = $stmt->fetch();

	//if post does not exists redirect user.
	if($row['postID'] == ''){
		header('Location: ./');
		exit;
	}
	if ($row['postOwner'] == '') {
		$postOwner = 'Dima';
	}

	$stmt = $db->prepare('SELECT * FROM blog_comments WHERE postID = :postID');
	$stmt->execute(array(':postID' => $_GET['id']));
	$comments = $stmt->fetchAll();
	
	if (!isset($_SESSION['username'])) { 
		$_SESSION['username'] = '';
	}
	
	$stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
	$stmt->execute(array(':username' => $_SESSION['username']));
	$user = $stmt->fetch();
	
	if (!isset($user)) {
		$user['memberID'] = 1;
	}

} catch (PDOException $e) {
	echo $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
    	<title>Blog - <?php echo $row['postTitle'];?></title>
    	<style>
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
		<?php include '../profile/header0.php';
    	include '../profile/header01.php';
		$topmenu = 4;
		$rightmenu = 0;
        ?>
    </head>
    <body>
    	<?php 
		include '../profile/header1.php';
		include '../profile/topmenu.php';
		include '../profile/header2.php';
		include '../profile/header3.php';
		?>
		<span>
			<div id="wrapper">
		
				<h1>Blog</h1>
				<hr />
				<p><a class="bloga" href="./">Blog Index</a></p>
					
				<?php	
					$blogp = 'blogp';
					echo '<div>';
						echo '<h1>'.$row['postTitle'].'</h1>';
						echo '<p id='.$blogp.'>Posted on '.date('M jS Y', strtotime($row['postDate'])).'</p>';
						echo '<p id='.$blogp.'>'.$row['postCont'].'</p>';				
					echo '</div>';
				?>
				<?php	if (isset($comments)) { ?>
						<p style='margin-left:50px'><b>Comments</b></p>
						<?php foreach ($comments as $comm) { 
							$commentID = $comm['commentID'];
							$date = strtotime($comm['commentDate']);
							$date = date('m-d-Y H:i:s', $date);
						?>
							<div style="color:black"><?php echo $comm['commentOwner'] ?>&nbsp;on&nbsp;<?php echo $date ?></div>
							<div style='border:1px solid #999;'>
							<?php echo '<p id='.$blogp.'>'.$comm['commentCont'].'</p>'; ?>
							</div><br />
						<?php }
					}
				?>
			</div>
<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		$_POST = array_map( 'stripslashes', $_POST );

		//collect form data
		extract($_POST);

		//very basic validation
		if ($comment == '') {
			$error[] = 'Please enter some content';
		}
		if ($commentOwner == '') {
			$error[] = 'Name can not be empty';
		}
		
		if(!isset($error)){

			try {
				
				//insert into database
				$stmt = $db->prepare('INSERT INTO blog_comments (postID, parentID, commentCont, commentOwner, commentDate, ownerID) VALUES (:postID, :parentID, :commentCont, :commentOwner, :commentDate, :ownerID) ') ;
				$stmt->execute(array(
					':postID' => $postID,
					':parentID' => $commentID == '' ? '1' : $commentID,
					':commentCont' => $comment,
					':commentOwner' => $commentOwner,
					':commentDate' => date('Y-m-d H:i:s'),
					':ownerID' => $user['memberID'] == 0 ? 1 : $user['memberID']
				));
				$commentID = $db->lastInsertId();
				echo date('m-d-Y H:i:s');
				
				/*$stmt = $db->prepare('INSERT INTO tree_paths (ancestor, descendant) VALUES (:ancestorid, :descendantid)') ;
				$stmt->execute(array(
					':ancestorid' => $commentID - 1,
					':descendantid' => $commentID
				));*/

				//redirect to index page
				header('Location: viewpost.php?id='.$postID);
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
			<?php //if not logged in redirect to login page
			if($usero->is_logged_in()){ ?>
				<form action='' method='post'>
					<input type="hidden" name="postID" value="<?php echo $row['postID']; ?>" />
					<input type="hidden" name="parentID" value="<?php echo $comment['commentID']; ?>" />
					<input type="hidden" name="ownerID" value="<?php echo $user['memberID']; ?>" />
			
					<p><label>Name</label><br />
					<input type="text" name="commentOwner" value="<?php if(isset($error)){ echo $_POST['commentOwner'];}?>" />
					<p><label>Content</label><br />
					<textarea name='comment' cols='60' rows='10'><?php if(isset($error)){ echo $_POST['comment'];}?></textarea></p>
			
					<p><input type='submit' name='submit' class="btn btn-default" value='Submit'></p>
				</form>
			<?php } ?>
		</span>
		<?php
		include '../profile/footer.php';
		include '../ptofile/counter.php';
		?>
	</body>
</html>