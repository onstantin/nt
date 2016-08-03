<?php
	session_start();
	error_reporting(E_ALL);
	session_destroy(); 
	header("Location: index.php");
