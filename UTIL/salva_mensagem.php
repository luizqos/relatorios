<!--**
 * @author Cesar Szpak - Celke -   cesar@celke.com.br
 * @pagina desenvolvida usando framework bootstrap,
 * o código é aberto e o uso é free,
 * porém lembre -se de conceder os créditos ao desenvolvedor.
 *-->
<?php
	session_start();
	include_once('conexao.php');
	if(empty($_POST['nome'])){
		$_SESSION['vazio_nome'] = "Campo nome é obrigatório";
		$url = 'http://localhost/Aula/form_contato.php';
		echo "
			<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=$url'>
		";
	}else{
		$_SESSION['value_nome'] = $_POST['nome'];
	}
	
	if(empty($_POST['email'])){
		$_SESSION['vazio_email'] = "Campo e-mail é obrigatório";
		$url = 'http://localhost/Aula/form_contato.php';
		echo "
			<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=$url'>
		";
	}else{
		$_SESSION['value_email'] = $_POST['email'];
	}
	
	if(empty($_POST['assunto'])){
		$_SESSION['vazio_assunto'] = "Campo assunto é obrigatório";
		$url = 'http://localhost/Aula/form_contato.php';
		echo "
			<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=$url'>
		";
	}else{
		$_SESSION['value_assunto'] = $_POST['assunto'];
	}
	
	if(empty($_POST['mensagem'])){
		$_SESSION['vazio_mensagem'] = "Campo mensagem é obrigatório";
		$url = 'http://localhost/Aula/form_contato.php';
		echo "
			<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=$url'>
		";
	}else{
		$_SESSION['value_mensagem'] = $_POST['mensagem'];
	}
	
	$nome = mysqli_real_escape_string($conn, $_POST['nome']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$assunto = mysqli_real_escape_string($conn, $_POST['assunto']);
	$mensagem = mysqli_real_escape_string($conn, $_POST['mensagem']);
	
	
	$result_msg_contato = "INSERT INTO mensagens_contatos(nome, email, assunto, mensagem, created) VALUES ('$nome', '$email', '$assunto', '$mensagem', NOW())";
	$resultado_msg_contato= mysqli_query($conn, $result_msg_contato)
?>