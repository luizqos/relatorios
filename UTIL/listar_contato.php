<!--**
 * @author Cesar Szpak - Celke -   cesar@celke.com.br
 * @pagina desenvolvida usando framework bootstrap,
 * o código é aberto e o uso é free,
 * porém lembre -se de conceder os créditos ao desenvolvedor.
 *-->
 <?php
	session_start();
	require_once "conexao_mssql_zeloi.php";
	//include_once('conexao.php');

try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("SELECT Inscricao, Nome, TipoVenda, Data, DataEnvioContrato, Aceite FROM Associados");
    $resultado   = $query->fetchAll();
 
 }catch(Exception $e){
 
    echo $e->getMessage();
    exit;

 }	
	
 ?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Relatorio</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
	<head>
	<body>
		<?php
			//Verificar se esta sendo passado na URL a página atual, senão é atribuido a pagina
			$pagina=(isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
			
			//Selecionar todos os itens da tabela 
			//$result_msg_contato = "SELECT * FROM mensagens_contatos";
			//$resultado_msg_contato = mysqli_query($conn , $result_msg_contato);
			
			//Contar o total de itens
			//$total_msg_contatos = mysqli_num_rows($resultado_msg_contato);
			
			//Seta a quantidade de itens por página
			//$quantidade_pg = 20;
			
			//calcular o número de páginas 
			//$num_pagina = ceil($total_msg_contatos/$quantidade_pg);
			
			//calcular o inicio da visualizao	
			//$inicio = ($quantidade_pg*$pagina)-$quantidade_pg;
			
			//Selecionar  os itens da página
			//$result_msg_contatos = "SELECT * FROM mensagens_contatos limit $inicio, $quantidade_pg";
			//$resultado_msg_contatos = mysqli_query($conn , $result_msg_contatos);
			//$total_msg_contatos = mysqli_num_rows($resultado_msg_contatos);
			
		?>
		<div class="container theme-showcase" role="main">
			<div class="page-header">
				<h1>Lista de Mensagem Contatos</h1>
			</div>
			<div class="dropdown">
				<span class="glyphicon glyphicon-cog btn-lg text-success" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					<li><a href="form_contato.php">Cadastrar</a></li>
					<li><a href="gerar_planilha.php">Gerar Relatório Excel</a></li>
					<li><a href="#">Gerar Relatório PDF</a></li>
				</ul>
			</div>
			<div class="row espaco">
				<div class="pull-right">					
					<a href="form_contato.php"><button type='button' class='btn btn-sm btn-success'>Cadastrar</button></a>
					<a href="gerar_planilha.php"><button type='button' class='btn btn-sm btn-success'>Gerar Excel</button></a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table">
						<thead>
							<tr>
								<th class="text-center">Inscricao</th>
								<th class="text-center">Nome</th>
								<th class="text-center">Tipo</th>
								<th class="text-center">Data</th>
								<th class="text-center">Data Envio</th>
								<th class="text-center">Aceite</th>
								<th class="text-center">Ação</th>
							</tr>
						</thead>
						<tbody>
							<?php
							   foreach($resultado as $r) {
							?>
								<tr>
									<td class="text-center"><?php echo $r['Inscricao']; ?></td>
									<td class="text-center"><?php echo $r['Nome']; ?></td>
									<td class="text-center"><?php echo $r['TipoVenda']; ?></td>
									<td class="text-center"><?php echo $r['Data']; ?></td>
									<td class="text-center"><?php echo $r['DataEnvioContrato']; ?></td>
									<td class="text-center"><?php echo $r['Aceite']; ?></td>
									<td class="text-center">								
										<a href="#">
											<span class="glyphicon glyphicon-eye-open text-primary" aria-hidden="true"></span>
										</a>
										<a href="#">
											<span class="glyphicon glyphicon-pencil text-warning" aria-hidden="true"></span>
										</a>
										<a href="#">
											<span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
										</a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
<!--			
			<?php
				//Verificar pagina anterior e posterior
				$pagina_anterior = $pagina - 1;
				$pagina_posterior = $pagina + 1;
			?>
			<nav class="text-center">
				<ul class="pagination">
					<li>
						<?php 
							if($pagina_anterior != 0){
								?><a href="administrativo.php?link=50&pagina=<?php echo $pagina_anterior; ?>" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
								</a><?php
							}else{
								?><span aria-hidden="true">&laquo;</span><?php
							}
						?>
					</li>
					<?php
						//Apresentar a paginação
						for($i = 1; $i < $num_pagina + 1; $i++){
							?>
								<li><a href="administrativo.php?link=50&pagina=<?php echo $i; ?>">
									<?php echo $i; ?>
								</a></li>
							<?php
						}
					?>
					<li>
						<?php 
							if($pagina_posterior <= $num_pagina){
								?><a href="administrativo.php?link=50&pagina=<?php echo $pagina_posterior; ?>" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
								</a><?php
							}else{
								?><span aria-hidden="true">&raquo;</span><?php
							}
						?>
					</li>
				</ul>
			</nav> -->
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
