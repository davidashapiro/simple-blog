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

			header('Location: logout.php');
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

		<?php include '../../profile/header0.php';
		$topmenu = 4;
		$rightmenu = 0;
		$menupage = 2;
        ?>
        <link rel="stylesheet" href="style.css" />

		<script language='Javascript' type='text/javascript'>
			function deluser(id, title)
			{
				if (confirm("Are you sure you want to delete '" + title + "'"))
				{
					window.location.href = 'users.php?deluser=' + id;
				}
			}
		</script>
		<style>
			table#usertable {width:100%; text-align:left; border:1px solid #DDDDDD; font-size:12px; color:#000;background:#fff; margin-bottom:10px;}
			table#usertable th {background-color:#E5E5E5; border:1px solid #BBBBBB; padding:3px 6px; font-weight:normal; color:#000;}
			table#usertable tr td {border:1px solid #DDDDDD; padding:5px 6px;}
			table#usertable tr.alt td {background-color:#E2E2E2;}
			table#usertable tr:hover {background-color:#F0F0F0; color:#000;}
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
		<?php
		include '../../profile/footer.php';
		include '../../profile/counter.php';
		?>
	</body>
</html>
