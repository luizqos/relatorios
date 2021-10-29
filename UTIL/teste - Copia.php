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
		<title>Gerenciador de Relatórios | Grupo Zelo</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">

	<head>
	<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Relatórios</a>
    </div>
    <div class="collapse navbar-collapse" id="header-menu">
      <ul class="nav navbar-nav navbar-right">
		<li><a href="gerar_planilha.php">Gerar Relatório</a></li>
		<li><a href="#" onClick="window.print()">Imprimir</a></li>
      </ul>
    </div>
  </div>
</nav>	
		<?php
			//Verificar se esta sendo passado na URL a página atual, senão é atribuido a pagina
			$pagina=(isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
		
		?>
		<div class="container theme-showcase" role="main">
			<div class="page-header">
				<h2><b>Titulo do Relatório</b></h2>
				</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="text-center">Inscricao</th>
								<th class="text-center">Nome</th>
								<th class="text-center">Tipo</th>
								<th class="text-center">Data</th>
								<th class="text-center">Data Envio</th>
								<th class="text-center">Aceite</th>
							</tr>
						<tbody>
							<?php
							   foreach($resultado as $r) {
							?>
								<tr>
									<td class="text-center"><?php echo $r['Inscricao']; ?></td>
									<td class="text-center"><?php echo strtoupper($r['Nome']); ?></td>
									<td class="text-center"><?php echo strtoupper($r['TipoVenda']); ?></td>
									<td class="text-center"><?php echo $r['Data']; ?></td>
									<td class="text-center"><?php echo $r['DataEnvioContrato']; ?></td>
									<td class="text-center"><?php echo $r['Aceite']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
						</thead>	
					</table>
				</div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
