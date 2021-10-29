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
$banco  = $_POST ["banco"];
$datainicio  = converteData($_POST ["datainicio"]);
$datafim  = converteData($_POST ["datafim"]);	
$res_query = "SELECT DISTINCT
Associados.Inscricao AS INSCRICAO,
Associados.NOME AS CLIENTE,
--Vendedores.Nome AS VENDEDOR,
--Coordenadores.Nome AS COORDENADOR,
Cidades.Nome AS CIDADE,
CONVERT(VARCHAR(10), Associados.Data, 103) AS DATA_CADASTRO,
CONVERT(VARCHAR(10), CarenFuneral, 103) AS CARENCIA,
Associados.Aceite AS ACEITE,
Associados.TipoAceite AS TIPO_ACEITE,
CONVERT(VARCHAR(10), Associados.DataAceite, 103) AS DATA_ACEITE,
Inscricao.Valor AS VALOR_INSCRICAO,
CONVERT(VARCHAR(10), Inscricao.Pagamento, 103) AS PGTO_INSCRICAO,
--IIF((Extrato.DataHora IS NULL), '', (CONVERT(VARCHAR(10),(select top 1 DataHora from Extrato WHERE Referencia IN ('1', '01') and Inscricao = Associados.Inscricao), 103))) AS DATA_BAIXA,
--Extrato.TipoDoc AS FORMA_PGTO,
--Mensalidade.Valor AS VALOR_MENSALIDE,
--IIF((Mensalidade.Valor <> NULL), '', (CONVERT(VARCHAR(10),(SELECT TOP 1 VALOR FROM Mensalidade WHERE Inscricao = Associados.Inscricao ORDER BY VENCIMENTO DESC)))) AS VALOR_MENSALIDADE,
TipoCobranca.TipCobDescricao AS TIPO_COBRANCA
FROM associados
LEFT JOIN Vendedores ON VENDEDORES.Codigo = Associados.Vendedor
LEFT JOIN Coordenadores ON Coordenadores.Codigo = Associados.Coordenador
LEFT JOIN Inscricao ON Inscricao.Inscricao = Associados.Inscricao
LEFT JOIN Extrato ON Extrato.Inscricao = Associados.Inscricao
LEFT JOIN Mensalidade ON Mensalidade.Inscricao = Associados.Inscricao
LEFT JOIN TipoCobranca ON TipoCobranca.TipCobCodigo = Associados.AssTipoCobranca
LEFT JOIN Cidades ON Cidades.Codigo = Associados.Cidade
WHERE ((Inscricao.Pagamento between '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999') OR (Inscricao.vencimento between '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999')) 
AND Associados.Aceite = 'S'
GROUP BY Mensalidade.Valor, Associados.Inscricao, Associados.Nome, Vendedores.Nome, Coordenadores.Nome, Associados.Data, Associados.TipoAceite, Associados.DataAceite, Inscricao.Valor, extrato.TipoDoc, TipoCobranca.TipCobDescricao, Cidades.Nome, Inscricao.Pagamento, Associados.Aceite,Extrato.DataHora, Associados.CarenFuneral
ORDER BY Associados.nome";
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
		<li><a href="gerar-vendas-carencia.php?datai=<?php echo $datainicio; ?>&dataf=<?php echo $datafim; ?>">Gerar Relatório</a></li>
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
				<h2><b>Relatório de Vendas</b></h2>
				</div>
			<div class="row">
				<div class="row justify-content-between">
					<table class="table table-striped">
						<thead>
							<tr>
							   <th>INSCRICAO</th>
							   <th>CLIENTE</th>
							   <th>CIDADE</th>
							   <th>DATA DE CADASTRO</th>
							   <th>CARENCIA</th>
							   <th>ACEITE</th>
							   <th>TIPO DE ACEITE</th>
							   <th>DATA DO ACEITE</th>
							   <th>VALOR DA INSCRIÇÃO</th>
							   <th>PAGAMENTO DA INSCRIÇÃO</th>
							   <th>TIPO DE COBRANÇA</th>
							</tr>
						<tbody>
							<?php
							   foreach($resultado as $r) {
							?>
								<tr>
									<td><?php echo $r['INSCRICAO']; ?></td>
									<td><?php echo strtoupper($r['CLIENTE']); ?></td>
									<td><?php echo strtoupper($r['CIDADE']); ?></td>
									<td><?php echo $r['DATA_CADASTRO']; ?></td>
									<td><?php echo $r['CARENCIA']; ?></td>
									<td><?php echo $r['ACEITE']; ?></td>
									<td><?php echo $r['TIPO_ACEITE']; ?></td>
									<td><?php echo $r['DATA_ACEITE']; ?></td>
									<td><?php 
											if ($r['VALOR_INSCRICAO']>0){
												echo number_format ($r['VALOR_INSCRICAO'], 2,',','.');
											}else{
												echo $r['VALOR_INSCRICAO'];
											}
										?>
									</td>
									<td><?php echo $r['PGTO_INSCRICAO']; ?></td>
									<td><?php echo $r['TIPO_COBRANCA']; ?></td>
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
