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
			
			$stmt = $db->prepare('DELETE FROM blog_comments WHERE parentID = :postID');
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
		<?php include '../../profile/header0.php';
		$topmenu = 4;
		$rightmenu = 0;
		$menupage = 1;
        ?>
        <link rel="stylesheet" href="style.css" />

		<script language='Javascript' type='text/javascript'>
			function delpost(id, title)
			{
				if (confirm("Are you sure you want to delete '" + title + "'"))
				{
					window.location.href = 'index.php?delpost=' + id;
				}
			}
		</script>
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
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>
