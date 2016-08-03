<?php
	session_start();
	error_reporting(E_ALL);
	require_once "city.class.php";	
	require_once "profile.class.php";
	
	if (isset($_POST['id'])) 
	{
		$_SESSION['id'] = htmlspecialchars($_POST['id']);		
		$user = new Profile($_SESSION['id']);		
	}	
	else if (isset($_SESSION['id'])) 
	{
		$user = new Profile($_SESSION['id']);
	}
?>

<?php if (!isset($_SESSION['id'])): ?>
	<form action="" method="post">
		<input type="text" name="id" placeholder="Введите idVK" required>
		<input type="submit" value="OK">
	</form>
<?php else: ?>
	<p><?= $user->name ?></p>
	<p><?= $user->surname ?></p>
	<p><?= $user->city ?></p>
	<p><img src="<?= $user->photo ?>"></p>
	<p><a href="edit.php">Изменить</a></p>	
	<p><a href="logout.php">Выйти</a></p>	
<?php endif; ?>