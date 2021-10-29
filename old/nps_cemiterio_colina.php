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

function converteData($data){
    if(count(explode("/",$data)) > 1): 
         return implode("-",array_reverse(explode("/",$data)));
    elseif(count(explode("-",$data)) > 1): 
         return implode("/",array_reverse(explode("-",$data)));
    endif;
}
$cemiterio = $_POST ["cemiterio"];
if ($cemiterio == 1){
$nomecemiterio = 'Belo Horizonte';	
}else
{
$nomecemiterio = 'Niteroi';
}	
$banco  = $_POST ["banco"];
$datainicio  = converteData($_POST ["datainicio"]);
$datafim  = converteData($_POST ["datafim"]);	
$res_query = "SELECT associados.NOME, 
	CELULAR = case
					when dbo.TiraLetras(Celular) is not null 
					and dbo.TiraLetras(Celular) <> ' ' 
					and Celular not like '0%'
					and len(dbo.tiraletras(Celular)) >= 9  
					and (substring(dbo.tiraletras(Celular), 3, 1) in('9', '8', '7') 
							OR substring(dbo.tiraletras(Celular), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(celular), 3,9)
					then dbo.TiraLetras(Celular)
					when dbo.TiraLetras(Telefone2) is not null 
					and dbo.TiraLetras(Telefone2) <> ' '
					and Telefone2 not like '0%'
					and len(dbo.tiraletras(Telefone2)) >= 9 
					and (substring(dbo.tiraletras(Telefone2), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Telefone2), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(telefone), 3,9)
					then dbo.tiraletras(Telefone2)
					when dbo.TiraLetras(Telefone) is not null 
					and dbo.TiraLetras(Telefone) <> ' '
					and Telefone not like '0%'
					and len(dbo.tiraletras(Telefone)) >= 9 
					and (substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Telefone), 1, 1) in('9', '8', '7')
						)
					then dbo.TiraLetras(Telefone)
					--then substring(dbo.TiraLetras(telefone2), 3,9)
				end, 
Cidades.nome CIDADE, 
Cidades.uf UF, 
Associados.inscricao INSCRICAO, 
Grupos.descricao FUNERARIA,  Associados.Grupo
FROM associados
INNER JOIN cidades ON associados.cidade = cidades.codigo
INNER JOIN grupos ON associados.grupo = grupos.grupo
WHERE associados.status in(1,2) and 
associados.Inscricao in (select inscricao from obitos where sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')
and associados.filial = ('$cemiterio')
AND 
(Celular not like '0%' 
	and dbo.TiraLetras(Celular) is not null 
	and dbo.TiraLetras(Celular) <> ' ' 
	and substring(dbo.tiraletras(Celular), 3, 1) in('9', '8', '7')
	and LEN(dbo.TiraLetras(Celular)) >= 9 and LEN(dbo.TiraLetras(Celular)) <= 11
	OR 
	Telefone2 not like '0%'
	and dbo.TiraLetras(Telefone2) is not null 
	and dbo.TiraLetras(Telefone2) <> ' '
	and substring(dbo.tiraletras(Telefone2), 3, 1) in('9', '8', '7')
	and LEN(dbo.TiraLetras(Telefone2)) >= 9 and LEN(dbo.TiraLetras(Telefone2)) <= 11
	OR 
	Telefone not like '0%'
	and dbo.TiraLetras(Telefone) is not null 
	and dbo.TiraLetras(Telefone) <> ' '
	and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
	and LEN(dbo.TiraLetras(Telefone)) >= 9 and LEN(dbo.TiraLetras(telefone)) <= 11
)
ORDER BY  Associados.Grupo, associados.nome";
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
		<li><a href="gerar-nps_cemiterio_colina.php?datai=<?php echo $datainicio; ?>&dataf=<?php echo $datafim; ?>&cemiterio=<?php echo $cemiterio; ?>">Gerar Relatório</a></li>
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
				<h2><b>Relatório de Cemitérios - Colina <?php echo $nomecemiterio; ?></b></h2>
				</div>
			<div class="row">
				<div class="row justify-content-between">
					<table class="table table-striped">
						<thead>
							<tr>
							   <th>NOME</th>
							   <th>CELULAR</th>
							   <th>INSCRICAO</th>
							   <th>NOME</th>
							   <th>CIDADE</th>
							</tr>
						<tbody>
							<?php
							   foreach($resultado as $r) {
							?>
								<tr>
									<td><?php echo strtoupper($r['NOME']); ?></td>
									<td><?php echo $r['CELULAR']; ?></td>
									<td><?php echo $r['INSCRICAO']; ?></td>
									<td><?php echo strtoupper($r['NOME']); ?></td>
									<td><?php echo strtoupper($r['CIDADE']); ?></td>
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
