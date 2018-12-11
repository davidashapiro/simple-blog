<?php require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
    	<title>Blog</title>
    	<?php include '../profile/header0.php';
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
			<a id="back2Top" title="Back to top" href="#">&#10148;</a>
			<a style="float:right;" class="btn btn-default" href="/simple-blog/admin/">Admin</a>
			<div id="wrapper">
				<h1>Blog</h1>
				<hr />
				<?php
					try {
						$blogp = 'blogp';
						$bloga = 'bloga';
		
						$stmt = $db->query('SELECT postID, postTitle, postDesc, postDate FROM blog_posts ORDER BY postID DESC');
						while($row = $stmt->fetch()){
							
							echo '<div>';
								echo '<h1><a id='.$bloga.' href="viewpost.php?id='.$row['postID'].'">'.$row['postTitle'].'</a></h1>';
								echo '<p id='.$blogp.'>Posted on '.date('M jS Y H:i:s', strtotime($row['postDate'])).'</p>';
								echo '<p id='.$blogp.'>'.$row['postDesc'].'</p>';				
								echo '<p id='.$blogp.'><a id='.$bloga.' href="viewpost.php?id='.$row['postID'].'">Read More</a></p>';				
							echo '</div>';
						}
					} catch(PDOException $e) {
					    echo $e->getMessage();
					}
				?>
		
			</div>
		</span>
		<?php
		include '../profile/footer.php';
		include '../profile/counter.php';
		?>
	</body>
</html>