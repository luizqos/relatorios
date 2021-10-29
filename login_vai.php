<?php 
// Conexão com o banco de dados 
require "comum.php"; 
 
// Inicia sessões 
session_start(); 
 
// Recupera o login 
$login = isset($_POST["login"]) ? addslashes(trim($_POST["login"])) : FALSE; 
// Recupera a senha, a criptografando em MD5 
$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE; 
 
// Usuário não forneceu a senha ou o login 
if(!$login || !$senha) 
{ 
    echo "Você deve digitar sua senha e login!"; 
    exit; 
} 
 
/** 
* Executa a consulta no banco de dados. 
* Caso o número de linhas retornadas seja 1 o login é válido, 
* caso 0, inválido. 
*/
/*
$SQL = "SELECT id, nome, login, senha, postar 
FROM usuarios 
WHERE login = $login"; 
$result_id = @mysqli_query($SQL) or die("Erro no banco de dados!"); 
$total = @mysqli_num_rows($result_id); 
*/
$query = "SELECT id, nome, login, senha, tipo FROM usuarios WHERE login = '$login' and senha = '$senha'";
$result_id = mysqli_query($conexao,$query);
$total = mysqli_num_rows($result_id); 
// Caso o usuário tenha digitado um login válido o número de linhas será 1.. 
if($total == 1) 
{ 
    // Obtém os dados do usuário, para poder verificar a senha e passar os demais dados para a sessão 
    $dados = mysqli_fetch_array($result_id); 
 
    // Agora verifica a senha 
    if(!strcmp($senha, $dados["senha"])) 
    { 
        // TUDO OK! Agora, passa os dados para a sessão e redireciona o usuário 
        $_SESSION["id_usuario"]= $dados["id"]; 
        $_SESSION["nome_usuario"] = stripslashes($dados["nome"]); 
        $_SESSION["permissao"]= $dados["tipo"]; 
        if ($_SESSION["permissao"] == 'A')
        {
            header("Location: ./admin/index.php"); 
        }
        else
        {
            header("Location: card.php"); 
        }

        exit; 
    } 
    // Senha inválida 
    else
    { 
    echo "Senha Invalida!"; 
	echo $total;	
    exit; 
    } 
} 
    // Login inválido 
else
{ 

    echo "O login fornecido por você é inexistente!"; 
    exit; 
} 
?>