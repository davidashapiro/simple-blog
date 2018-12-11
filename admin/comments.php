<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: /simple-forum/login.php?page=blog'); }

//show message from add / edit page
if(isset($_GET['delpost']))
{ 
	try {
		
		$stmt = $db->query('SELECT * FROM blog_comments WHERE commentID = ' .$_GET['delpost'].' ORDER BY postID DESC');
		$row = $stmt->fetch();
		
		if ($row['ownerID'] == $_SESSION['memberID']) 
		{
			$stmt = $db->prepare('DELETE FROM blog_comments WHERE commentID = :commentID') ;
			$stmt->execute(array(':commentID' => $_GET['delpost']));
	
			header('Location: comments.php?action=deleted');
			exit;
		}
		header('Location: comments.php?action='.urlencode('User is not an owner and can not delete it.'));
		exit;
	} catch (PDOException $e) {
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
		$menupage = 3;
        ?>

		<script language='Javascript' type='text/javascript'>
			function delpost(id)
			{
				if (confirm("Are you sure you want to delete this comment?"))
				{
					window.location.href = 'comments.php?delpost=' + id;
				}
			}
		</script>
		<style>
			table#commentstable {
				width:100%; 
				text-align:left; 
				border:1px solid #DDDDDD; 
				font-size:12px; 
				color:#000;
				background:#fff; 
				margin-bottom:10px;
			}
			table#commentstable th {
				background-color:#E5E5E5; 
				border:1px solid #BBBBBB; 
				padding:3px 6px; 
				font-weight:normal; 
				color:#000;
			}
			table#commentstable tr td {border:1px solid #DDDDDD; padding:5px 6px;}
			table#commentstable tr.alt td {background-color:#E2E2E2;}
			table#commentstable tr:hover {background-color:#F0F0F0; color:#000;}
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
					echo '<h3 class="error">Post: '.urldecode($_GET['action']).'.</h3>'; 
				} 
				?>
		
				<table id="commentstable">
					<tr>
						<th>Comment</th>
						<th>Date</th>
						<th>Owner</th>
						<th>Action</th>
					</tr>
				<?php
					try {
			
						$stmt = $db->query('SELECT commentID, commentCont, commentDate, ownerID FROM blog_comments WHERE ownerID = '.$_SESSION['memberID'].' ORDER BY commentID DESC');
						
						while($row = $stmt->fetch())
						{
							$stmt2 = $db->prepare('SELECT username FROM users WHERE id = :id');
							$stmt2->execute(array(':id' => $row['ownerID']));
							$member = $stmt2->fetch();
							
							echo '<tr>';
							echo '<td>'.$row['commentCont'].'</td>';
							echo '<td width=100>'.date('M jS Y', strtotime($row['commentDate'])).'</td>';
							echo '<td>'.$member['username'].'</td>';
				?>
							<td width="80">
								<?php if ($row['ownerID'] == $_SESSION['memberID']) { ?>
									<a href="edit-comments.php?id=<?php echo $row['commentID'];?>">Edit</a> | 
									<a href="javascript:delpost('<?php echo $row['commentID'];?>')">Delete</a>
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
			</div>
		</span>
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>
