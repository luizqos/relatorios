<?php
$servidor = "localhost";
$usuario  = "root";
$senha = "";
$bd = "relatorios";

$conexao = mysqli_connect($servidor, $usuario, $senha, $bd);
mysqli_set_charset( $conexao, 'utf8');
?>