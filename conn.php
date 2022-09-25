<?php

$conn = new mysqli("localhost", "root", "", "peluqueria");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
?>