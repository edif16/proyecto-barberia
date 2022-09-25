<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['idProducto']) ? $_GET['idProducto'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == ''  || $token == ''){
  echo 'ERROR DE PETICION';
  exit;
}else {
  $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

  if ($token == $token_tmp){

    $sql = $con->prepare("SELECT count(idProducto) FROM servicio WHERE idProducto=? AND Activo=1");
    $sql->execute([$id]);
    if ($sql->fetchColumN() > 0) {
      
      $sql = $con->prepare("SELECT TipoProducto, Descripcion, Monto, Descuento FROM servicio 
      WHERE idProducto=? AND Activo=1 LIMIT 1");
      $sql->execute([$id]);
      $row = $sql->fetch(PDO::FETCH_ASSOC);
      $TipoProducto = $row['TipoProducto'];
      $Descripcion = $row['Descripcion'];
      $Monto = $row['Monto'];
      $Descuento = $row['Descuento'];
      $Precio_desc = $Monto - (($Monto * $Descuento) / 100);
      $dir_images = 'imagenes/servicios/' . $id . '/';

      $rutaImg = $dir_images . 'principal.jpg';

      if(!file_exists($rutaImg)){
        $rutaImg = 'imagenes/no-photo.jpg';
      }

      $images = array();
      $dir = dir($dir_images);

      while(($archivo = $dir->read()) != false){
        if($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))){
          $images[] = $dir_images . $archivo;
        }
      }
      $dir->close();
    }
  } else {
    echo 'ERROR DE PETICION';
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barberia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
    crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    
</head>
<body>

<header>
  <div class="navbar navbar-expand-lg  navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
      <img src="imagenes/Logo.jpg" width="65" height="65">
      </a>
      <button class="navbar-toggler" type="button" 
      data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" 
      aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="index.php" class="nav-link active">Productos</a>
          </li>
          <li class="nav-item">
            <a href="servicios.php" class="nav-link ">Servicios</a>
          </li>
          <li class="nav-item">
            <a href="ingresar.php" class="nav-link ">Ingresar</a>
          </li>
        </ul>

        <a href="checkout.php" class="btn" >
        <img src="imagenes/carrito.png" width="40" height="40"><span id="num_cart" class="badge bg-secondary">
          <?php echo $num_cart;?>
        </span>

        </a>


    </div>
  </div>
</header>

<main>
  <div class="container">
    <div class="row">
      <div class="col-md-6 order-md-1">


        <div id="carouselmages" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="<?php echo $rutaImg;?>" class="d-block w-100">
      </div>

      <?php foreach($images as $img) { ?>
        <div class="carousel-item">
        <img src="<?php echo $img;?>" class="d-block w-100">
        </div>
        <?php } ?>
    </div>
    <button class="carousel-control-prev" type="button" 
    data-bs-target="#carouselmages" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" 
    data-bs-target="#carouselmages" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>



        
      </div>
      <div class="col-md-6 order-md-2">
        <h2><?php echo $TipoProducto ?></h2>

        <?php if($Descuento > 0) {?>
          <p><del><?php echo MONEDA . number_format($Monto, 2, '.', '.'); ?></del></p>
          <h2>
            <?php echo MONEDA . number_format($Precio_desc, 2, '.', '.'); ?>
            <small class="text-success"><?php echo $Descuento; ?>% descuento</small>
          </h2>

          <?php } else { ?>

            <h2><?php echo MONEDA . number_format($Monto, 2, '.', '.'); ?></h2>
 
            <?php } ?>

        <p class="lead">
          <?php echo $Descripcion; ?>
        </p>

        <div class="d-grid  gap-3 col-10 mx-auto">
          <button class="btn-primary" type="button">Comprar Ahora</button>
          <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo
          $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
        </div>
      </div>
    </div>
  </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
crossorigin="anonymous"></script>

<script>
  function addProducto(id, token){
    let url = 'clases/carrito.php'
    let formData = new FormData()
    formData.append('id', id)
    formData.append('token', token)

    fetch(url, {
      method: 'POST',
      body: formData,
      mode: 'cors'
    }).then(responde => responde.json())
    .then(data => {
      if(data.ok){    
        let elemento = document.getElementById("num_cart")
        elemento.innerHTML = data.numero
        
      }
    })
  }
</script> 
    
</body>
</html>