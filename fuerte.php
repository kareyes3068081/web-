<?php
// VARIABLES DINÁMICAS
$titulo = "FUERTE";
$descripcion = "El plato fuerte es el alimento principal de la gastronomía de cada estado. 
Se caracteriza por ser más completo y sustancioso, y suele incluir carnes, pescados o guisos tradicionales. 
Estos platillos son representativos porque reflejan la historia, el clima y los recursos naturales del lugar donde fueron creados.";

$imagen = "img/2platillos/FUERTE.png";
$volver = "index.php"; // página a la que regresará
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Antojitos Mexicanos</title>

<link href="https://fonts.googleapis.com/css2?family=Lobster&family=Playpen+Sans:wght@300;500;700&display=swap" rel="stylesheet">

<style>
body{
  margin:0;
  background:#e9e1d2;
  font-family:"Playpen Sans", sans-serif;
  display:flex;
  justify-content:center;
  align-items:center;
  height:100vh;
}

.container{
  width:95%;
  max-width:1200px;
  background:#efe6d8;
  border-radius:40px;
  padding:25px;
  display:flex;
  gap:30px;
  box-sizing:border-box;
  border:2px solid #d34a3b;
}

.left{
  flex:1;
  background:#d9b6b6;
  border-radius:30px;
  padding:40px 30px 30px 30px;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  text-align:center;
}

.top-section{
  display:flex;
  align-items:center;
  gap:10px;
  margin-bottom:30px;
  justify-content:flex-start;
}

.question-circle{
  width:65px;
  height:65px;
  background:#f1e6d9;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
}

.question-circle img{
  width:35px;
}

.label{
  background:#f1e6d9;
  padding:12px 60px;
  border-radius:20px;
  font-weight:600;
  color:#c64535;
}

.title{
  font-family:"Lobster", cursive;
  font-size:80px;
  color:#d34a3b;
  margin:20px 0;
}

.description{
  font-size:16px;
  line-height:1.6;
  color:#2b2b2b;
  max-width:600px;
  margin:0 auto;
}

.btn-container{
  margin-top:30px;
}

.btn{
  background:#d34a3b;
  color:white;
  padding:15px 100px;
  border:none;
  border-radius:30px;
  font-family:"Lobster", cursive;
  font-size:28px;
  cursor:pointer;
  transition:0.2s ease;
}

.btn:hover{
  transform:translateY(-3px);
}

.right{
  flex:1;
  background:#cfcfcf;
  border-radius:30px;
  padding:20px;
  position:relative;
  display:flex;
  justify-content:center;
  align-items:center;
}

.inner-panel{
  background:linear-gradient(to bottom right,#f3ede6,#e7dfd4);
  border-radius:25px;
  width:90%;
  height:90%;
  overflow:hidden;
}

.antojitos-img{
  width:100%;
  height:100%;
  object-fit:cover;
  border-radius:25px;
}

.pin{
  position:absolute;
  top:20px;
  right:20px;
  width:45px;
}
</style>
</head>

<body>

<div class="container">

  <div class="left">
    <div>
      <div class="top-section">
        <div class="question-circle">
          <img src="img/1mapa/signo.png" alt="Signo">
        </div>

        <div class="label">¿Qué es un ...</div>
      </div>

      <div class="title"><?php echo $titulo; ?></div>

      <div class="description">
        <?php echo $descripcion; ?>
      </div>
    </div>

    <div class="btn-container">
      <a href="<?php echo $volver; ?>">
        
      </a>
    </div>
  </div>

  <div class="right">
    <img src="img/1mapa/PunteroMapa.png" class="pin">
    <div class="inner-panel">
      <img src="<?php echo $imagen; ?>" class="antojitos-img">
    </div>
  </div>

</div>

</body>
</html>