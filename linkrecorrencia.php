 <?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

function converteData($data){
    if(count(explode("/",$data)) > 1): 
         return implode("-",array_reverse(explode("/",$data)));
    elseif(count(explode("-",$data)) > 1): 
         return implode("/",array_reverse(explode("-",$data)));
    endif;
}
$inscricao  = $_POST ["inscricao"];
$banco  = $_POST ["banco"];
$res_query = "select  A.inscricao INSCRICAO
			, IIF (A.ASSTIPOCOBRANCA = 7, 'COBRANÇA RECORRENTE', IIF(A.ASSTIPOCOBRANCA = 1, 'BOLETO', CONVERT(VARCHAR(10),A.ASSTIPOCOBRANCA))) AS TIPOCOBRANCA
			, A.payzentokencard PTK
			, A.nome NOME
			, A.CPF CPF
			, S.EmailEnvio EMAIL
			, CONCAT('https://contrato.grupozelo.com/#/home/', S.HashLink) AS LINK
			, IIF (S.FormaEnvio = 1, 'SMS', IIF(S.FormaEnvio = 2, 'EMAIL', IIF(S.FormaEnvio = 3, 'WPP', ''))) AS FORMAENVIO
			, A.STATUS STATUS
			, S.TipoAceite TIPOACEITE
			, IIF(S.StatusLink = 2, 'S', 'N') AS ACEITE
			, CONCAT( CONVERT(VARCHAR(10), S.UpdateAt, 103), ' - ', CONVERT(VARCHAR(8), S.UpdateAt, 108) ) AS DATAACEITE
			, CONCAT( CONVERT(VARCHAR(10), S.CreateAt, 103), ' - ', CONVERT(VARCHAR(8), S.CreateAt, 108) ) AS DATACONTRATO
			, IIF (S.TipoAceite = 1, 'ACEITE', IIF(S.TipoAceite = 2, 'ACEITE + ADESÃO', IIF(S.TipoAceite = 3, 'ACEITE + ADESÃO + COBRANÇA CONTINUADA', ' - '))) AS DESCRICAO
			from Associados as A
			LEFT JOIN signatureorderlink AS S ON S.Inscricao = A.Inscricao
			where A.Inscricao = '$inscricao'
			order by s.CreateAt desc";
require_once "conn/".$banco."";


try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("$res_query");
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
		<link href="assets/fontawesome/css/all.css" rel="stylesheet">
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
		<div class="container" role="main">
			<div class="page-header">
				<h2><b>Aceite Eletrônico</b></h2>
				</div>
			<div class="row">
				<div class="row justify-content-between">
					<table class="table table-striped">
						<thead>
							<tr>
							   <th>INSCRICAO</th>
							   <th>COBRANCA</th>
							   <th>PAYZEN</th>
							   <th>NOME</th>
							   <th>CPF</th>
							   <th>EMAIL</th>
							   <th>LINK</th>
							   <th>ENVIO</th>
							   <th>STATUS</th>
							   <th>TIPO ACEITE</th>
							   <th>ACEITE</th>
							   <th>DATA ACEITE</th>
							   <th>ENVIO</th>
							   <th>DESCRICAO</th>
							</tr>
						<tbody>
							<?php
							   foreach($resultado as $r) {
								if ($r['FORMAENVIO'] == 'WPP')
								{
									$icone = 'fab fa-whatsapp-square';
									$cor = 'green';
								}else
								if ($r['FORMAENVIO'] == 'SMS')
								{
									$icone = 'fas fa-sms';
									$cor = 'purple';
								}
								else
								if ($r['FORMAENVIO'] == 'EMAIL')
								{
									$icone = 'fas fa-envelope';
									$cor = 'orange';
								}

							?>
								<tr>
									<td><?php echo $r['INSCRICAO']; ?></td>
									<td><?php echo $r['TIPOCOBRANCA']; ?></td>
									<td><?php echo $r['PTK']; ?></td>
									<td><?php echo strtoupper($r['NOME']); ?></td>
									<td><?php echo $r['CPF']; ?></td>
									<td><?php echo strtolower($r['EMAIL']); ?></td>
									<td style="font-size: 1.5em"><a href="<?php echo $r['LINK']; ?>" target="_blank"><i class="fas fa-link"></i></a></td>
									<td style="font-size: 2.5em; color: <?php echo $cor; ?>;"><i class="<?php echo $icone; ?>"></i></td>
									<td><?php echo $r['STATUS']; ?></td>
									<td><?php echo $r['TIPOACEITE']; ?></td>
									<td><?php echo $r['ACEITE']; ?></td>
									<td><?php echo $r['DATAACEITE']; ?></td>
									<td><?php echo $r['DATACONTRATO']; ?></td>
									<td><?php echo strtoupper($r['DESCRICAO']); ?></td>
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
