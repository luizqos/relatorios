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
    $arquivo = 'VENDAS_CARENCIA'.$datainicio.'a'.$datafim.'.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>INSCRICAO</th>";
    $dadosXls .= "          <th>CLIENTE</th>";
    $dadosXls .= "          <th>CIDADE</th>";
    $dadosXls .= "          <th>DATA DE CADASTRO</th>";
    $dadosXls .= "          <th>CARENCIA</th>";	
    $dadosXls .= "          <th>ACEITE</th>";
    $dadosXls .= "          <th>TIPO DE ACEITE</th>";
    $dadosXls .= "          <th>DATA DO ACEITE</th>";	
    $dadosXls .= "          <th>VALOR DA INSCRIÇÃO</th>";
    $dadosXls .= "          <th>PAGAMENTO DA INSCRIÇÃO</th>";
    $dadosXls .= "          <th>TIPO DE COBRANÇA</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['CLIENTE'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['CIDADE'])."</td>";
        $dadosXls .= "          <td>".$res['DATA_CADASTRO']."</td>";
        $dadosXls .= "          <td>".$res['CARENCIA']."</td>";
		$dadosXls .= "          <td>".$res['ACEITE']."</td>";
		$dadosXls .= "          <td>".$res['TIPO_ACEITE']."</td>";
		$dadosXls .= "          <td>".$res['DATA_ACEITE']."</td>";
		$dadosXls .= "          <td>".number_format ($res['VALOR_INSCRICAO'], 2,',','.')."</td>";
        $dadosXls .= "          <td>".$res['PGTO_INSCRICAO']."</td>";
        $dadosXls .= "          <td>".$res['TIPO_COBRANCA']."</td>";
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