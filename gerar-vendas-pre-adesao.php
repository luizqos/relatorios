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
    $query      = $Conexao->query("SELECT A.Inscricao
    , A.Grupo
    , A.SubGrupo
    , A.TipoVenda
    , V.Nome AS Vendedor
    , S.Nome AS Supervisor
    , A.Nome
    , I.Valor
    , CONVERT(VARCHAR(10), I.Vencimento, 103) AS Vencimento
    , CONVERT(VARCHAR(10), I.Pagamento, 103) AS Pagamento
    , TC.TipCobDescricao AS Cobranca
    , A.Aceite
    , A.TipoAceite
    , CONVERT(VARCHAR(10), A.DataAceite, 103) AS DataAceite
    FROM associados AS A
    INNER JOIN Inscricao AS I ON I.Inscricao = A.Inscricao
    LEFT JOIN TipoCobranca AS TC ON TC.TipCobCodigo = A.AssTipoCobranca
    LEFT JOIN Vendedores AS V ON V.Codigo = A.Vendedor
    LEFT JOIN Supervisores AS S ON S.Codigo = A.Supervisor
    WHERE A.Grupo IN ('PRE','ZELO', 'ZP')
    AND A.Aceite = 'N'
    AND A.LinkVenda IS NOT NULL
    AND I.Pagamento IS NOT NULL
    --AND A.SubGrupo NOT IN ('ZP', 'ZPE')
    AND (I.Vencimento BETWEEN '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999')");
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
		$arquivo = 'VENDAS-PRE-POR-DATA-ADESAO-'.$datainicio.'a'.$datafim.'.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>INSCRICAO</th>";
    $dadosXls .= "          <th>CLIENTE</th>";
    $dadosXls .= "          <th>GRUPO</th>";
    $dadosXls .= "          <th>SUBGRUPO</th>";	
    $dadosXls .= "          <th>TIPO DE VENDA</th>";
    $dadosXls .= "          <th>VENDEDOR</th>";
    $dadosXls .= "          <th>SUPERVISOR</th>";
    $dadosXls .= "          <th>VALOR</th>";
    $dadosXls .= "          <th>VENCIMENTO</th>";
	$dadosXls .= "          <th>PAGAMENTO</th>";
    $dadosXls .= "          <th>COBRANÇA</th>";
    $dadosXls .= "          <th>ACEITE</th>";
    $dadosXls .= "          <th>TIPO DE ACEITE</th>";
    $dadosXls .= "          <th>DATA DO ACEITE</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['Inscricao']."</td>";
        $dadosXls .= "          <td>".$res['Nome']."</td>";
        $dadosXls .= "          <td>".$res['Grupo']."</td>";
        $dadosXls .= "          <td>".$res['SubGrupo']."</td>";
        $dadosXls .= "          <td>".$res['TipoVenda']."</td>";
        $dadosXls .= "          <td>".$res['Vendedor']."</td>";
        $dadosXls .= "          <td>".$res['Supervisor']."</td>";
        $dadosXls .= "          <td>".number_format($res['Valor'], 2,',','.')."</td>";
        $dadosXls .= "          <td>".$res['Vencimento']."</td>";
		$dadosXls .= "          <td>".$res['Pagamento']."</td>";
        $dadosXls .= "          <td>".$res['Cobranca']."</td>";
        $dadosXls .= "          <td>".$res['Aceite']."</td>";
        $dadosXls .= "          <td>".$res['TipoAceite']."</td>";
        $dadosXls .= "          <td>".$res['DataAceite']."</td>";
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