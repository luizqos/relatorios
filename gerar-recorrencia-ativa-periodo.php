 <?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

$datainicio  = $_GET['datai'];
$datafim  = $_GET['dataf'];
require_once "conn/conexao_mssql_zelo.php";

try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("select A.inscricao as INSCRICAO
, A.nome NOME

,IIF((U.Nome = 'GOLD'), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuInsert
WHERE Associados.INSCRICAO = A.inscricao), U.Nome) as USUARIO

,IIF((U.SETOR > 0), (SELECT Descricao FROM setorusuarios WHERE codigo = U.SETOR), '') as SETOR_USUARIO

,IIF((A.UsuAtivouRecorrencia IS NOT NULL), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuAtivouRecorrencia
WHERE Associados.INSCRICAO = A.inscricao), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuInsert
WHERE Associados.INSCRICAO = A.inscricao)) AS USUARIO_REC

,IIF((A.UsuAtivouRecorrencia IS NOT NULL), (SELECT Descricao 
FROM Usuarios
LEFT JOIN setorusuarios
ON Usuarios.setor = setorusuarios.codigo
WHERE Usuarios.codigo = A.UsuAtivouRecorrencia), '') as SETOR_USUARIO_REC
, V.Nome AS VENDEDOR
,CONVERT(VARCHAR(10), O.data_cadastro, 103) DATA_CADASTRO
, O.status STATUS
, max(M.Valor) VALORMENSALIDADE

from associados AS A
inner join orderRequest AS O 
on A.inscricao = O.inscricao

inner join usuarios AS U 
on A.UsuUpdate = U.Codigo

inner join mensalidade AS M 
on A.inscricao = M.inscricao

left join Vendedores AS V
ON A.Vendedor = V.Codigo

left join setorusuarios AS S
ON U.setor = S.codigo

where A.AssTipoCobranca = 7 and A.inscricao not in(21500) 
--and E.Usuario like '%payzen%'
and O.status = 'A' and U.nome not in('ANA LUISA DUARTE', 'ROBERTA FERNANDA NUNES')
and O.data_cadastro between '$datainicio 00:00:00.000' and '$datafim 23:59:59.999'
and a.Grupo not in ('ZP')
--and CONVERT(VARCHAR(10), A.DataUpdate, 103) = CONVERT(VARCHAR(10), O.data_cadastro, 103)
group by A.inscricao, A.nome, A.DataUpdate, U.nome, O.status, O.data_cadastro, O.subscriptionId, A.UsuAtivouRecorrencia, A.Vendedor, V.Nome, S.Descricao, U.setor
order by O.data_cadastro asc");
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
		$arquivo = 'RECORRENCIA_ATIVA.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>INSCRICAO</th>";
    $dadosXls .= "          <th>CLIENTE</th>";
    $dadosXls .= "          <th>USUARIO</th>";
	$dadosXls .= "          <th>SETOR USUARIO</th>";
    $dadosXls .= "          <th>USUARIO RECORRENCIA</th>";
	$dadosXls .= "          <th>SETOR USUARIO RECORRENCIA</th>";
    $dadosXls .= "          <th>VENDEDOR</th>";
    $dadosXls .= "          <th>DATA DE CADASTRO</th>";
    $dadosXls .= "          <th>STATUS</th>";
	$dadosXls .= "          <th>VALOR DA MENSALIDADE</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['USUARIO'])."</td>";
		$dadosXls .= "          <td>".strtoupper($res['SETOR_USUARIO'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['USUARIO_REC'])."</td>";
		$dadosXls .= "          <td>".strtoupper($res['SETOR_USUARIO_REC'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['VENDEDOR'])."</td>";
        $dadosXls .= "          <td>".$res['DATA_CADASTRO']."</td>";
		$dadosXls .= "          <td>".$res['STATUS']."</td>";
        $dadosXls .= "          <td>".number_format($res['VALORMENSALIDADE'], 2,',','.')."</td>";
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