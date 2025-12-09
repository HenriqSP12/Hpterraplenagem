<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'sistema_maquinas';


$conn = new mysqli($host, $user, $pass, $db);

if($conn -> connect_error){
    die("Falha ao conectar" . $conn->connect_error);
}
?>




