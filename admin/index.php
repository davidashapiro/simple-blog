<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: /simple-forum/login.php?page=blog'); }

//show message from add / edit page
if(isset($_GET['delpost']))
{ 
	try {
		$stmt = $db->query('SELECT postID, postTitle, postDate, postOwner FROM blog_posts WHERE postID = ' .$_GET['delpost'].' ORDER BY postID DESC');
		$row = $stmt->fetch();
		
		if ($row['postOwner'] == $_SESSION['username']) {
			$stmt = $db->prepare('DELETE FROM blog_posts WHERE postID = :postID') ;
			$stmt->execute(array(':postID' => $_GET['delpost']));
	
			header('Location: index.php?action=deleted');
			exit;
		}
		header('Location: index.php?action='.urlencode('Only the owner can delete tjis post.'));
		exit;
	}
	catch (PDOException $e) {
		echo $e->getMessage();
	}
} 

?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin</title>
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
			function delpost(id, title)
			{
				if (confirm("Are you sure you want to delete '" + title + "'"))
				{
					window.location.href = 'index.php?delpost=' + id;
				}
			}
		</script>
		<?php $menupage = 1; ?>
		<style>
			table#blogtable {
				width:100%; 
				text-align:left; 
				border:1px solid #DDDDDD; 
				font-size:12px; 
				color:#000;
				background:#fff; 
				margin-bottom:10px;
			}
			table#blogtable th {
				background-color:#E5E5E5; 
				border:1px solid #BBBBBB; 
				padding:3px 6px; 
				font-weight:normal; 
				color:#000;
			}
			table#blogtable tr td {border:1px solid #DDDDDD; padding:5px 6px;}
			table#blogtable tr.alt td {background-color:#E2E2E2;}
			table#blogtable tr:hover {background-color:#F0F0F0; color:#000;}
			
			#adminmenu {
				padding-left: 0;
			}

			#adminmenu li {
				float: left;
				list-style: none;
				margin-right: 20px;
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
		
				<?php 
				//show message from add / edit page
				if(isset($_GET['action'])){ 
					echo '<h3>Post: '.urldecode($_GET['action']).'.</h3>'; 
				} 
				?>
		
				<table id="blogtable">
					<tr>
						<th>Title</th>
						<th>Date</th>
						<th>Owner</th>
						<th>Action</th>
					</tr>
				<?php
					try {
			
						$stmt = $db->query('SELECT postID, postTitle, postDate, postOwner FROM blog_posts ORDER BY postID DESC');
						while($row = $stmt->fetch())
						{
							echo '<tr>';
							echo '<td>'.$row['postTitle'].'</td>';
							echo '<td width=100>'.date('M jS Y', strtotime($row['postDate'])).'</td>';
							echo '<td>'.$row['postOwner'].'</td>';
				?>
							<td width="80">
								<?php if($row['postOwner'] == $_SESSION['username']){?>
									<a href="edit-post.php?id=<?php echo $row['postID'];?>">Edit</a> | 
									<a href="javascript:delpost('<?php echo $row['postID'];?>','<?php echo $row['postTitle'];?>')">Delete</a>
								<?php } ?>
							</td>
				<?php 
							echo '</tr>';
						}
					} catch(PDOException $e) 
					{
					    echo $e->getMessage();
					}
				?>
				</table>
				<p><a class="btn btn-default" href='add-post.php'>Add Post</a></p>
			</div>
		</span>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
