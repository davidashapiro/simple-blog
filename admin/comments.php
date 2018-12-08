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
			function delpost(id)
			{
				if (confirm("Are you sure you want to delete this comment?"))
				{
					window.location.href = 'comments.php?delpost=' + id;
				}
			}
		</script>
		<?php $menupage = 3; ?>
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
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
