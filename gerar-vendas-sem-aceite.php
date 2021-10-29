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
    $query      = $Conexao->query("SELECT DISTINCT
	Associados.Inscricao AS INSCRICAO,
	Associados.NOME AS CLIENTE,
	Vendedores.Nome AS VENDEDOR,
	Coordenadores.Nome AS COORDENADOR,
	CONVERT(VARCHAR(10), Associados.Data, 103) AS DATA_CADASTRO,
	Associados.GRUPO AS GRUPO,
	Associados.Aceite AS ACEITE,
	Associados.TipoAceite AS TIPO_ACEITE,
	Inscricao.Valor AS VALOR_INSCRICAO,
	CONVERT(VARCHAR(10), Inscricao.Pagamento, 103) AS PGTO_INSCRICAO,
	IIF((Extrato.DataHora IS NULL), '', (CONVERT(VARCHAR(10),(select DataHora from Extrato WHERE Referencia IN ('1', '01') and Inscricao = Associados.Inscricao), 103))) AS DATA_BAIXA,
	IIF((Mensalidade.Valor <> NULL), '', (CONVERT(VARCHAR(10),(SELECT TOP 1 VALOR FROM Mensalidade WHERE Inscricao = Associados.Inscricao ORDER BY VENCIMENTO DESC)))) AS VALOR_MENSALIDADE
FROM associados
LEFT JOIN Vendedores ON VENDEDORES.Codigo = Associados.Vendedor
LEFT JOIN Coordenadores ON Coordenadores.Codigo = Associados.Coordenador
LEFT JOIN Inscricao ON Inscricao.Inscricao = Associados.Inscricao
LEFT JOIN Extrato ON Extrato.Inscricao = Associados.Inscricao
LEFT JOIN Mensalidade ON Mensalidade.Inscricao = Associados.Inscricao
INNER JOIN STATUS ON Associados.Status = STATUS.Codigo
WHERE Associados.Data between '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999'
AND Inscricao.Pagamento IS NOT NULL
AND Associados.TipoAceite = 1
AND Associados.Aceite = 'N'
GROUP BY Mensalidade.Valor, Associados.Inscricao, Associados.Nome, Vendedores.Nome, Coordenadores.Nome, Associados.Data, Associados.TipoAceite, Associados.DataAceite, Inscricao.Valor, extrato.TipoDoc, Inscricao.Pagamento, Associados.Aceite, Extrato.DataHora, Associados.Grupo
ORDER BY Associados.nome");
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
		$arquivo = 'Vendas sem aceite com Adesao Paga.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>INSCRICAO</th>";
    $dadosXls .= "          <th>CLIENTE</th>";
    $dadosXls .= "          <th>VENDEDOR</th>";	
    $dadosXls .= "          <th>COORDENADOR</th>";
    $dadosXls .= "          <th>DATA DE CADASTRO</th>";	
	$dadosXls .= "          <th>GRUPO</th>";	
    $dadosXls .= "          <th>ACEITE</th>";
    $dadosXls .= "          <th>TIPO DE ACEITE</th>";
    $dadosXls .= "          <th>VALOR DA INSCRIÇÃO</th>";
    $dadosXls .= "          <th>PAGAMENTO DA INSCRIÇÃO</th>";
    $dadosXls .= "          <th>DATA DA BAIXA</th>";
	$dadosXls .= "          <th>VALOR MENSALIDADE</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['CLIENTE'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['VENDEDOR'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['COORDENADOR'])."</td>";
        $dadosXls .= "          <td>".$res['DATA_CADASTRO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['GRUPO'])."</td>";		
		$dadosXls .= "          <td>".$res['ACEITE']."</td>";
		$dadosXls .= "          <td>".$res['TIPO_ACEITE']."</td>";
		$dadosXls .= "          <td>".number_format ($res['VALOR_INSCRICAO'], 2,',','.')."</td>";
		$dadosXls .= "          <td>".$res['PGTO_INSCRICAO']."</td>";
		$dadosXls .= "          <td>".$res['DATA_BAIXA']."</td>";
		$dadosXls .= "          <td>".number_format ($res['VALOR_MENSALIDADE'], 2,',','.')."</td>";		
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