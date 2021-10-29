 <?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

require_once "conn/conexao_mssql_zelo.php";

$datainicio  = $_GET['datai'];
$datafim  = $_GET['dataf'];
try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("select A.inscricao INSCRICAO
, A.nome NOME
,U.Nome USUARIO

,IIF((A.UsuAtivouRecorrencia IS NOT NULL), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuAtivouRecorrencia
WHERE Associados.INSCRICAO = A.inscricao), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuInsert
WHERE Associados.INSCRICAO = A.inscricao)) AS USUARIO_REC

, CONVERT(VARCHAR(10), O.data_cadastro, 103) DATA_CADASTRO, O.status STATUS
, O.subscriptionId SUBSCRIPITONID
, E.ValorPago VALORPAGO
, CONVERT(VARCHAR(10), M.Vencimento, 103) VENCIMENTO
, CONVERT(VARCHAR(10), E.Pagamento, 103) DATAPAGAMENTO
	--  A.SubGrupo SubGrupo,
	--CONVERT(VARCHAR(10), A.DataUpdate, 103) Data,
from associados AS A
inner join orderRequest AS O 
on A.inscricao = O.inscricao

inner join usuarios AS U 
on A.UsuUpdate = U.Codigo

left join extrato AS E on 
A.inscricao = E.inscricao

inner join mensalidade AS M
on E.inscricao = M.inscricao and E.Pagamento = M.Vencimento

where A.AssTipoCobranca = 7 and A.inscricao not in(21500) 
and E.Usuario like '%payzen%' 
and M.pagamento is not null
and O.status = 'A' 
and U.nome not in('ANA LUISA DUARTE', 'ROBERTA FERNANDA NUNES')
and M.pagamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.999'
--and A.DATA between '$datainicio' and (getdate() + 1)
--and (A.DataUpdate >= O.data_cadastro) 
and ((CONVERT(VARCHAR(10), A.DataUpdate, 103) = CONVERT(VARCHAR(10), O.data_cadastro, 103)) or (A.DataUpdate > O.data_cadastro))
--group by A.inscricao, A.nome, A.DataUpdate, U.nome, O.status, O.data_cadastro, O.subscriptionId, E.inscricao
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
    $arquivo = 'RELATORIO DE QUITAÇÃO'.$datainicio.'a'.$datafim.'.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>INSCRICAO</th>";
    $dadosXls .= "          <th>NOME</th>";
    $dadosXls .= "          <th>USUARIO</th>";
	$dadosXls .= "          <th>USUARIO RECORRENTE</th>";
	$dadosXls .= "          <th>DATA DE CADASTRO</th>";
    $dadosXls .= "          <th>STATUS</th>";
	$dadosXls .= "          <th>SUBSCRIPITONID</th>";
    $dadosXls .= "          <th>VALOR PAGO</th>";
    $dadosXls .= "          <th>VENCIMENTO</th>";
    $dadosXls .= "          <th>DATA DO PAGAMENTO</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['USUARIO'])."</td>";
		$dadosXls .= "          <td>".strtoupper($res['USUARIO_REC'])."</td>";
		$dadosXls .= "          <td>".$res['DATA_CADASTRO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['STATUS'])."</td>";
		$dadosXls .= "          <td>".$res['SUBSCRIPITONID']."</td>";
        $dadosXls .= "          <td>".number_format($res['VALORPAGO'], 2,',','.')."</td>";
        $dadosXls .= "          <td>".$res['VENCIMENTO']."</td>";
		$dadosXls .= "          <td>".$res['DATAPAGAMENTO']."</td>";
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