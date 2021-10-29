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
    $query      = $Conexao->query("SELECT 
  A.Inscricao
, A.NOME														AS 'Nome'
, V.NOME														AS 'Vendedor'
, C.NOME														AS 'Cidade'
, CONVERT(VARCHAR(10), A.DataEnvioContrato, 103)				AS 'DtEnvioContrato'
, A.TIPOACEITE													AS 'Tipo Aceite'
, A.Aceite													    AS 'Aceite'
, S.DESCRICAO													AS 'Status'
, CONVERT(VARCHAR(10), A.DataAceite, 103)						AS 'DtAceite'
FROM ASSOCIADOS A
INNER JOIN VENDEDORES V		ON V.CODIGO = A.VENDEDOR
INNER JOIN Status S			ON S.Codigo = A.Status
INNER JOIN CIDADES C		ON C.CODIGO = A.CIDADE
INNER JOIN INSCRICAO I		ON I.INSCRICAO = A.INSCRICAO
WHERE 
A.DATA between '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999'
AND A.LINKVENDA IS NOT NULL
AND A.DataEnvioContrato IS NOT NULL
--AND I.PARCELA = '1'
ORDER BY A.INSCRICAO");
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
		$arquivo = 'Relatorio_aceite.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>INSCRICAO</th>";
    $dadosXls .= "          <th>CLIENTE</th>";
    $dadosXls .= "          <th>VENDEDOR</th>";	
    $dadosXls .= "          <th>CIDADE</th>";
	$dadosXls .= "          <th>ENVIO DO CONTRATO</th>";
    $dadosXls .= "          <th>TIPO DE ACEITE</th>";
	$dadosXls .= "          <th>ACEITE</th>";
    $dadosXls .= "          <th>DATA DO ACEITE</th>";	
    $dadosXls .= "          <th>STATUS</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['Inscricao']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['Nome'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['Vendedor'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['Cidade'])."</td>";
		$dadosXls .= "          <td>".$res['DtEnvioContrato']."</td>";
		$dadosXls .= "          <td>".$res['Tipo Aceite']."</td>";
		$dadosXls .= "          <td>".$res['Aceite']."</td>";
		$dadosXls .= "          <td>".$res['DtAceite']."</td>";
		$dadosXls .= "          <td>".$res['Status']."</td>";			
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