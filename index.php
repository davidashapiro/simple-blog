<?php require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
    	<title>Blog</title>
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
    	<!--<link rel="stylesheet" href="style/normalize.css">-->
    	<!--<link rel="stylesheet" href="style/main.css">-->
	</head>
	<body>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/header_part3.js'></script>
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
								echo '<p id='.$blogp.'>Posted on '.date('jS M Y H:i:s', strtotime($row['postDate'])).'</p>';
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
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>

	</body>
</html>