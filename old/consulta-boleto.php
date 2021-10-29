<!--**
 * @author Cesar Szpak - Celke -   cesar@celke.com.br
 * @pagina desenvolvida usando framework bootstrap,
 * o código é aberto e o uso é free,
 * porém lembre -se de conceder os créditos ao desenvolvedor.
 *-->
 <?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 


$banco  = $_POST ["banco"];
$campo = $_POST ["campo"];
$busca = $_POST ["busca"];
$referencia = $_POST ["referencia"];
if ($campo == 1){
$res_query = "
            select top 1000 BoletoContaCodigo AS CONTA
                , BancoCodigo AS CODIGO_BANK
                , CONCAT(BancoAgencia, '-', BancoCodigoDv) AS BANCO_AG
                , CONCAT(BancoConta, '-', BancoContaDv) AS BANCO_CONTA
                , BancoCarteiraCodigo AS CARTEIRA
                , CONVERT(VARCHAR(10), TituloVencimento, 103) AS VENCIMENTO
                , TituloNumeroDocumento AS REFERENCIA
                , TituloNossoNumero AS NOSSO_NUMERO
                , TituloSacado AS CLIENTE
                , TituloInscricao AS INSCRICAO
                , TituloValorDocumento AS VALOR
                , CONVERT(VARCHAR(10), DataInsert, 103) AS CRIADO
                , usuarios.Nick as USUARIO
                , Extrato.ValorPago AS VLR_PAGAMENTO
                , CONVERT(VARCHAR(10), Extrato.Pagamento, 103) AS DATA_PAGAMENTO
                , EXTRATO.Usuario AS USUARIO_BAIXA
            from BoletoTitulo 
            inner join Usuarios on usuarios.Codigo = BoletoTitulo.Usuinsert
            left join Extrato on (CONCAT (EXTRATO.Inscricao,'-',Extrato.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
            where TituloNossoNumero LIKE '%".$busca."'
            ORDER BY BoletoTitulo.DataInsert DESC
";
}
if ($campo == 2)
{
$res_query = "
            select top 1000 BoletoContaCodigo AS CONTA
                , BancoCodigo AS CODIGO_BANK
                , CONCAT(BancoAgencia, '-', BancoCodigoDv) AS BANCO_AG
                , CONCAT(BancoConta, '-', BancoContaDv) AS BANCO_CONTA
                , BancoCarteiraCodigo AS CARTEIRA
                , CONVERT(VARCHAR(10), TituloVencimento, 103) AS VENCIMENTO
                , TituloNumeroDocumento AS REFERENCIA
                , TituloNossoNumero AS NOSSO_NUMERO
                , TituloSacado AS CLIENTE
                , TituloInscricao AS INSCRICAO
                , TituloValorDocumento AS VALOR
                , CONVERT(VARCHAR(10), DataInsert, 103) AS CRIADO
                , usuarios.Nick as USUARIO
                , Extrato.ValorPago AS VLR_PAGAMENTO
                , CONVERT(VARCHAR(10), Extrato.Pagamento, 103) AS DATA_PAGAMENTO
                , EXTRATO.Usuario AS USUARIO_BAIXA
            from BoletoTitulo 
            inner join Usuarios on usuarios.Codigo = BoletoTitulo.Usuinsert
            left join Extrato on (CONCAT (EXTRATO.Inscricao,'-',Extrato.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
            where dbo.TiraLetras(TituloLinhaDigitavel)  = '".$busca."'
            ORDER BY BoletoTitulo.DataInsert DESC
";
}if ($campo == 3)
{
  $res_query = "
              select top 1000 BoletoContaCodigo AS CONTA
                  , BancoCodigo AS CODIGO_BANK
                  , CONCAT(BancoAgencia, '-', BancoCodigoDv) AS BANCO_AG
                  , CONCAT(BancoConta, '-', BancoContaDv) AS BANCO_CONTA
                  , BancoCarteiraCodigo AS CARTEIRA
                  , CONVERT(VARCHAR(10), TituloVencimento, 103) AS VENCIMENTO
                  , TituloNumeroDocumento AS REFERENCIA
                  , TituloNossoNumero AS NOSSO_NUMERO
                  , TituloSacado AS CLIENTE
                  , TituloInscricao AS INSCRICAO
                  , TituloValorDocumento AS VALOR
                  , CONVERT(VARCHAR(10), DataInsert, 103) AS CRIADO
                  , usuarios.Nick as USUARIO
                  , Extrato.ValorPago AS VLR_PAGAMENTO
                  , CONVERT(VARCHAR(10), Extrato.Pagamento, 103) AS DATA_PAGAMENTO
                  , EXTRATO.Usuario AS USUARIO_BAIXA
              from BoletoTitulo 
              inner join Usuarios on usuarios.Codigo = BoletoTitulo.Usuinsert
              left join Extrato on (CONCAT (EXTRATO.Inscricao,'-',Extrato.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
              where TituloInscricao = ".$busca." AND TituloNumeroDocumento LIKE '%".$referencia."%'
              ORDER BY BoletoTitulo.DataInsert DESC
  ";
  }

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
		<li><a href="#">Gerar Relatório</a></li>
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
				<h2><b>Boletos</b></h2>
				</div>
			<div class="row">
				<div class="row justify-content-between">
					<table class="table table-striped">
						<thead>
							<tr>
                <th style="width: 2%">CART.</th>
                <th style="width: 5%">INSCRICAO</th>
                <th style="width: 70%">CLIENTE</th>
                <th style="width: 2%">COD. CONTA</th>
                <th style="width: 2%">BANCO</th>
                <th style="width: 2%">AG.</th>
                <th style="width: 2%">CONTA</th>
                <th style="width: 2%">REF.</th>
                <th style="width: 2%">NOSSO NUMERO</th>
                <th style="width: 2%">VENC.</th>
                <th style="width: 2%">VALOR TITULO</th>
                <th style="width: 2%">DATA PGTO</th>
                <th style="width: 2%">VALOR PGTO</th>
                <th style="width: 2%">BAIXA POR:</th>
                <th style="width: 2%">CRIADO EM:</th>
                <th style="width: 2%">CRIADO POR:</th>
							</tr>
						<tbody>
							<?php
							   foreach($resultado as $r) {
							?>
								<tr>
                  <td><?php echo strtoupper($r['CARTEIRA']); ?></td>
                  <td><?php echo strtoupper($r['INSCRICAO']); ?></td>
                  <td><?php echo strtoupper($r['CLIENTE']); ?></td>
                  <td><?php echo strtoupper($r['CONTA']); ?></td>
                  <td><?php echo strtoupper($r['CODIGO_BANK']); ?></td>
                  <td><?php echo strtoupper($r['BANCO_AG']); ?></td>
                  <td><?php echo strtoupper($r['BANCO_CONTA']); ?></td>
                  <td><?php echo strtoupper($r['REFERENCIA']); ?></td>
                  <td><?php echo strtoupper($r['NOSSO_NUMERO']); ?></td>
                  <td><?php echo strtoupper($r['VENCIMENTO']); ?></td>
                  <td><?php echo number_format ($r['VALOR'], 2,',','.'); ?></td>
                  <td><?php echo strtoupper($r['DATA_PAGAMENTO']); ?></td>
                  <td><?php echo number_format ($r['VLR_PAGAMENTO'], 2,',','.'); ?></td>
                  <td><?php echo strtoupper($r['USUARIO_BAIXA']); ?></td>
                  <td><?php echo strtoupper($r['CRIADO']); ?></td>
                  <td><?php echo strtoupper($r['USUARIO']); ?></td>
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
