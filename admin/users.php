<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$usero->is_logged_in()){ header('Location: /simple-forum/login.php?page=blog'); }

//show message from add / edit page
if(isset($_GET['deluser'])){ 

	try 
	{
		if ($_GET['deluser'] == $_SESSION['memberID']) 
		{
			$stmt = $db->prepare('DELETE FROM users WHERE id = :id') ;
			$stmt->execute(array(':id' => $_GET['deluser']));

			header('Location: users.php?action=deleted');
			exit;
		}
		header('Location: users.php?action='.urlencode('Only an owner can delete himself.'));
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
		<title>Admin - Users</title>

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
			function deluser(id, title)
			{
				if (confirm("Are you sure you want to delete '" + title + "'"))
				{
					window.location.href = 'users.php?deluser=' + id;
				}
			}
		</script>
		<?php $menupage = 2; ?>
		<style>
			table#usertable {width:100%; text-align:left; border:1px solid #DDDDDD; font-size:12px; color:#000;background:#fff; margin-bottom:10px;}
			table#usertable th {background-color:#E5E5E5; border:1px solid #BBBBBB; padding:3px 6px; font-weight:normal; color:#000;}
			table#usertable tr td {border:1px solid #DDDDDD; padding:5px 6px;}
			table#usertable tr.alt td {background-color:#E2E2E2;}
			table#usertable tr:hover {background-color:#F0F0F0; color:#000;}
			
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
					echo '<h3>User: '.urldecode($_GET['action']).'.</h3>'; 
				} 
				?>
			
				<table id="usertable">
					<tr>
						<th>Username</th>
						<th>Email</th>
						<th>Avatar</th>
						<th>Action</th>
					</tr>
				<?php
					try {
						$stmt = $db->query('SELECT id, username, email, avatar FROM users ORDER BY username');
						while($row = $stmt->fetch())
						{
							echo '<tr>';
							echo '<td>'.$row['username'].'</td>';
							echo '<td>'.$row['email'].'</td>';
							if ($row['avatar'] != '') {
								echo '<td><img src="'.htmlentities($row['avatar']).'" alt="Image Perso" style="max-width:100px;max-height:100px;" /></td>';
							}
				?>
							<td>
								<?php if($row['id'] == $_SESSION['userid']){?>
									<a href="edit-user.php?id=<?php echo $row['id'];?>">Edit</a> 
									| <a href="javascript:deluser('<?php echo $row['id'];?>','<?php echo $row['username'];?>')">Delete</a>
								<?php } ?>
							</td>
				<?php 
							echo '</tr>';
						}
					} catch(PDOException $e) {
					    echo $e->getMessage();
					}
				?>
				</table>
				<p><a class="btn btn-default" href='add-user.php'>Add User</a></p>
			</div>
		</span>
		<script language='JavaScript' type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
