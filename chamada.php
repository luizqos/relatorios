<?php
$id_usuario = $_SESSION["id_usuario"];
$querypermissao = "SELECT * FROM usuario_relatorios where idUsuario = $id_usuario and idConsulta = $id_relatorio";
$result = mysqli_query($conexao,$querypermissao);
$total = mysqli_num_rows($result);
?>