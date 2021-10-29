 <?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

$datainicio  = $_GET['datai'];
$datafim  = $_GET['dataf'];
require_once "conn/conexao_mssql_belovale.php";

try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("SELECT associados.NOME, 
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
	Grupos.descricao FUNERARIA,  Associados.Grupo,
	'SEPULTAMENTO' SERVIÇO 
	FROM associados
	INNER JOIN cidades ON associados.cidade = cidades.codigo
	INNER JOIN grupos ON associados.grupo = grupos.grupo
	WHERE associados.status in(1,2) and 
	associados.Inscricao in (select inscricao from obitos where sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')
	--and associados.grupo = ''
	UNION ALL
	SELECT associados.NOME, 
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
	Grupos.descricao FUNERARIA,  Associados.Grupo,
	'CREMAÇAO' SERVIÇO 
	FROM associados
	INNER JOIN cidades ON associados.cidade = cidades.codigo
	INNER JOIN grupos ON associados.grupo = grupos.grupo
	WHERE associados.status in(1,2) and 
	associados.Inscricao in (select inscricao from obitos where Datacremacao between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')
	--and associados.grupo = ''
	UNION ALL
	SELECT associados.NOME, 
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
	Grupos.descricao FUNERARIA,  Associados.Grupo,
	'EXUMACAO' SERVIÇO 
	FROM associados
	INNER JOIN cidades ON associados.cidade = cidades.codigo
	INNER JOIN grupos ON associados.grupo = grupos.grupo
	WHERE associados.status in(1,2) and 
	associados.Inscricao in (select inscricao from exumacao where DataExumacao between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')
	--and associados.grupo = ''
	UNION ALL
	SELECT associados.NOME, 
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
	Grupos.descricao FUNERARIA,  Associados.Grupo,
	'TRANSLADO' SERVIÇO 
	FROM associados
	INNER JOIN cidades ON associados.cidade = cidades.codigo
	INNER JOIN grupos ON associados.grupo = grupos.grupo
	WHERE associados.status in(1,2) and 
	associados.Inscricao in (select inscricao from Translados where DataTranslados between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')
	--and associados.grupo = ''
");
    $result   = $query->fetchAll();
 
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

	<head>
	<body>
		<?php
		// Definimos o nome do arquivo que será exportado
		$arquivo = 'NPS_BELOVALE.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>NOME</th>";
    $dadosXls .= "          <th>CELULAR</th>";
    $dadosXls .= "          <th>INSCRICAO</th>";
	$dadosXls .= "          <th>NOME</th>";
	$dadosXls .= "          <th>CIDADE</th>";	
	$dadosXls .= "          <th>SERVIÇO</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
		$dadosXls .= "          <td>".$res['CELULAR']."</td>";
		$dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
		$dadosXls .= "          <td>".strtoupper($res['CIDADE'])."</td>";
		$dadosXls .= "          <td>".strtoupper($res['SERVIÇO'])."</td>";
        $dadosXls .= "      </tr>";
    }
    $dadosXls .= "  </table>";
		
    // Configurações header para forçar o download  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    // Se for o IE9, isso talvez seja necessário
    header('Cache-Control: max-age=1');
       
    // Envia o conteúdo do arquivo  
    echo $dadosXls;  
    exit;
?>
	</body>
</html>