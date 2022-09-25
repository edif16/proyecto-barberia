<?php
	session_start();
	include('conn.php');

	$query=$conn->query("select * from login where email='".$_SESSION['email']."'");
	$row=$query->fetch_array();

	$email=$row['email'];
?>