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
		<li><a href="gerar-vendas.php?datai=<?php echo $datainicio; ?>&dataf=<?php echo $datafim; ?>">Gerar Relatório</a></li>
		<li><a href="#" onClick="window.print()">Imprimir</a></li>
      </ul>
    </div>
  </div>
</nav>	
		<?php
			//Verificar se esta sendo passado na URL a página atual, senão é atribuido a pagina
			$pagina=(isset($_GET['pagina'])) ? $_GET['pagina'] : 1;


  // Dados do banco
  	$server = '52.170.149.151';
	$port = '1443'; // porta padrão
	$server = $port !== '1443' && is_string($port) ? $server .= ", $port": $server;
	$database = 'zelo';
	$user = 'Luiz';
	$pass = '@ferrugem123';


  $conninfo = array("Database" => $database, "UID" => $user, "PWD" => $pass);
  $conn = sqlsrv_connect($server, $conninfo);
  
  $instrucaoSQL = "SELECT Inscricao, Nome from Associados where Inscricao = 88051348";
  
  $params = array();
  $options =array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $consulta = sqlsrv_query($conn, $instrucaoSQL, $params, $options);
  $numRegistros = sqlsrv_num_rows($consulta);
  echo "Esta tabela contém $numRegistros registros!";
  
  if ($numRegistros!=0) {
	echo "Esta tabela contém 1 registros!";
  }else
  {
	echo "Esta tabela contém 0 registros!";
  }
?>

	
		
		?>
		<div class="container" role="main">
			<div class="page-header">
				<h2><b>Relatório de Vendas <?php echo $numRegistros; ?></b></h2>
				</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
