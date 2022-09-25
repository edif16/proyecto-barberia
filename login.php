<?php 
	include('conn.php');
	session_start();
	if(isset($_POST['email'])){
		$username=$_POST['email'];
		$password=md5($_POST['password']);

		$query=$conn->query("select * from user where email='$username' and password='$password'");

		if ($query->num_rows>0){
			$row=$query->fetch_array();
			$_SESSION['email']=$row['emailid']; 
		}
		else{
			?>
  				<span>Login Failed. User not Found.</span>
  			<?php 
		}
	}
?>