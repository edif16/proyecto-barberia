<?php

define("KEY_TOKEN", "@.Pru3b@_16#");
define("MONEDA", "Q");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}
