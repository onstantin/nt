<?php
	session_start();
	error_reporting(E_ALL);
	require_once "city.class.php";	
	require_once "profile.class.php";
	
	$user = new Profile($_SESSION['id']);
	
	if (isset($_POST['name']) && isset($_POST['surname'])) 
	{
		$user->setNewName($_POST['name'], $_POST['surname']);	
		
		if (isset($_FILES['file'])) 
		{
			$user->setNewPhoto($_FILES['file']['tmp_name']);
		}		
		
		header("Location: index.php");
	}	
?>

<?php if (isset($_SESSION['id'])): ?>
	<form action="" method="post" enctype="multipart/form-data">
		<input type="text" name="name" value="<?= $user->name ?>" required>
		<input type="text" name="surname" value="<?= $user->surname ?>" required>
		<input type="file" name="file">
		<input type="submit" value="OK">
	</form>
	<p><a href="logout.php">Выйти</a></p>	
<?php endif; ?>