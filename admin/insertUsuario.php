<?php

$errors = array(); //To store errors
$form_data = array(); //Pass back the data to `form.php`

/* Validate the form on the server side */
if (empty($_POST['nome'])) { //Name cannot be empty
    $errors['nome'] = 'Name cannot be blank';
}
if (empty($_POST['login'])) { //Name cannot be empty
    $errors['login'] = 'Name cannot be blank';
}
if (empty($_POST['senha'])) { //Name cannot be empty
    $errors['senha'] = 'Name cannot be blank';
}
if (empty($_POST['tipo'])) { //Name cannot be empty
    $errors['tipo'] = 'Name cannot be blank';
}
if (empty($_POST['status'])) { //Name cannot be empty
    $errors['status'] = 'Name cannot be blank';
}
if (!empty($errors)) { //If errors in validation
    $form_data['success'] = false;
    $form_data['errors']  = $errors;
}
else { //If not, process the form, and return true on success
    $form_data['success'] = true;
    $form_data['posted'] = 'Data Was Posted Successfully';
}

//Return the data back to form.php
echo json_encode($form_data);

// RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !
$nome	= $_POST ["nome"];	//atribuição do campo "nome" vindo do formulário para variavel	
$login	= $_POST ["login"];	//atribuição do campo "email" vindo do formulário para variavel
$senha	= $_POST ["senha"];	//atribuição do campo "ddd" vindo do formulário para variavel
$tipo	= $_POST ["tipo"];	//atribuição do campo "ddd" vindo do formulário para variavel
$status	= $_POST ["status"];	//atribuição do campo "telefone" vindo do formulário para variavel

include('conexao.php');
$data = $conn->query("INSERT INTO `usuarios` ( `nome` , `login` , `senha` , `tipo`, `status`) 
VALUES ('$nome', '$login', MD5('$senha'), '$tipo', '$status')");
?> 