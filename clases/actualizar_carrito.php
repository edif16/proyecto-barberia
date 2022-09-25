<?php 
require '../config/config.php';
require '../config/database.php';

if(isset($_POST['action'])){
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if ($action == 'agregar'){
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        if($respuesta>0){
            $datos['oh'] = true;
        } else {
            $datos['ok'] = false;
        }
        $datos['sub'] = MONEDA . number_format($respuesta, 2, ',', '');
    } else {
        $datos['ok'] = false;
    }
}

echo json_encode($datos);

function agregar($id, $cantidad){
    $res = 0;
    if($id > 0 && $cantidad > 0 && is_numeric(($cantidad))){
        if(isset($_SESSION['carrito']['productos'][$id])){
            $_SESSION['carrito']['productos'][$id] = $cantidad;

            $db = new Database();
            $con = $db->conectar();
            
            $sql = $con->prepare("SELECT Monto, Descuento FROM producto WHERE idProducto=? AND Activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $Monto = $row['Monto'];
            $Descuento = $row['Descuento'];
            $Precio_desc = $Monto - (($Monto * $Descuento) / 100);
            $res = $cantidad * $Precio_desc;

            return $res;

        }
    } else {
        return $res;
    }
}