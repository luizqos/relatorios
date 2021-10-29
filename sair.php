<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

// Apaga todas as variáveis e encerra a sessão
session_destroy();
header("Location: card.php"); 
?>
