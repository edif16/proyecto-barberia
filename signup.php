<?php
	include('conn.php');
	if(isset($_POST['email'])){
		$email=$_POST['email'];
		$password=$_POST['spassword'];

		$query=$conn->query("select * from user where email='$email'");

		if ($query->num_rows>0){
			?>
  				<span>email already exist.</span>
  			<?php 
		}
        elseif (!preg_match("/^[a-zA-Z_]*$/",$Nombre)){
			?>
  				<span style="font-size:11px;">Invalid Nombre. Space & Special Characters not allowed.</span>
  			<?php 
		}

		elseif (!preg_match("/^[a-zA-Z0-9_]*$/",$email)){
			?>
  				<span style="font-size:11px;">Invalid email. Space & Special Characters not allowed.</span>
  			<?php 
		}
		elseif (!preg_match("/^[a-zA-Z0-9_]*$/",$password)){
			?>
  				<span style="font-size:11px;">Invalid password. Space & Special Characters not allowed.</span>
  			<?php 
		}
		else{
			$mpassword=md5($password);
			$conn->query("insert into user (username, password) values ('$username', '$mpassword')");
			?>
  				<span>Sign up Successful.</span>
  			<?php 
		}
	}
?>