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
    $query      = $Conexao->query("
    SELECT DISTINCT
    A.Inscricao AS INSCRICAO
, A.Nome AS CLIENTE
, A.CPF AS CPF
, A.Telefone AS TELEFONE1
, A.Telefone2 AS TELEFONE2
, A.Celular AS CELULAR
    , S.descricao AS STATUS
    , A.AssTipoCobranca AS TIPO_COBRANCA
    , A.TipoAceite AS TIPO_ACEITE
    , A.Aceite AS ACEITE
    , A.Grupo AS GRUPO
    , A.SubGrupo AS SUBGRUPO
    , A.TipoVenda AS TIPOVENDA
    , A.Supervisor AS SUPERVISOR
    , V.Nome AS VENDEDOR
    , IIF ((SELECT TOP 1 status FROM orderRequest WHERE inscricao  = A.Inscricao AND status = 'A') = 'A', 'SIM', 'NÃO') AS RECORRENTE
    ,ORIGEM_VENDA = CASE
        WHEN A.Coordenador = '503'
        THEN 'TLMK'
        WHEN A.Coordenador = 'ROB' OR A.Grupo IN ('ZP', 'ZPE')
        THEN 'PRIME'
        WHEN A.Vendedor = 'EC0' OR A.Grupo = 'SITE'
        THEN 'SITE'
        WHEN A.Vendedor = 'UST'
        THEN 'SANTA LUZIA'
        ELSE 'EXTERNO'
      END
    , CONVERT(varchar(10), IIF(A.DataInsert IS NULL, A.DATA, A.DataInsert), 103) AS DATA_VENDA
    , CONVERT(varchar(10), A.DataAceite, 103) AS DATA_ACEITE
    , CONVERT(varchar(10), I.Vencimento, 103) AS DATA_ADESÃO
    , CONVERT(varchar(10), I.Pagamento, 103) AS DATA_PAGAMENTO
    , IIF(M.datprom <> NULL, '', CONVERT(varchar(10), (SELECT TOP 1 datprom FROM Mensalidade WHERE Inscricao  = A.Inscricao AND datprom IS NOT NULL ORDER BY datprom DESC), 103)) AS DATA_IMPRESSAO
    , IIF (CE.DataSaida IS NULL, 'N/A', CONVERT(varchar(10), 
      (
        select top 1 DataSaida 
        from ControleEntrega 
        left join ControleEntregaItens
        on ControleEntregaItens.Codigo = ControleEntrega.Codigo
        where ControleEntregaItens.Inscricao = A.Inscricao order by DataSaida desc), 103)
      )	AS DATA_AGENDAMENTO
    FROM Associados AS A
    INNER JOIN STATUS AS S
    ON A.STATUS = S.CODIGO
    LEFT JOIN orderRequest AS O
    ON O.inscricao = A.Inscricao
    LEFT JOIN Inscricao AS I
    ON I.Inscricao = A.Inscricao
    LEFT JOIN Mensalidade AS M
    ON M.Inscricao = A.Inscricao
    LEFT JOIN ControleEntregaItens AS CEI
    ON CEI.Inscricao = A.Inscricao
    LEFT JOIN ControleEntrega AS CE
    ON CE.Codigo = CEI.Codigo
INNER JOIN Vendedores AS V
ON V.Codigo = A.Vendedor
WHERE ((A.Data BETWEEN '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999') and (A.Coordenador = 'ROB' OR A.Grupo IN ('ZP'))) 
   or ((I.Pagamento BETWEEN '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999') and (A.Coordenador = 'ROB' OR A.Grupo IN ('ZP')))
    ORDER BY A.INSCRICAO DESC
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
    $arquivo = 'RELATORIO VENDAS UP'.$datainicio.'a'.$datafim.'.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>CONTRATO</th>";
    $dadosXls .= "          <th>CLIENTE</th>";
    $dadosXls .= "          <th>CPF</th>";
    $dadosXls .= "          <th>TELEFONE 1</th>";
    $dadosXls .= "          <th>TELEFONE 2</th>";
    $dadosXls .= "          <th>CELULAR</th>";
    $dadosXls .= "          <th>STATUS</th>";
    $dadosXls .= "          <th>TIPO DE COBRANÇA</th>";
    $dadosXls .= "          <th>RECORRENTE</th>";
    $dadosXls .= "          <th>ACEITE</th>";
    $dadosXls .= "          <th>DATA DO ACEITE</th>";	
    $dadosXls .= "          <th>GRUPO</th>";
    $dadosXls .= "          <th>SUBGRUPO</th>";
    $dadosXls .= "          <th>TIPO DE VENDA</th>";	
    $dadosXls .= "          <th>SUPERVISOR</th>";
    $dadosXls .= "          <th>VENDEDOR</th>";
    $dadosXls .= "          <th>ORIGEM DA VENDA</th>";
    $dadosXls .= "          <th>DATA DA VENDA</th>";
    $dadosXls .= "          <th>DATA DA ADESÃO</th>";
    $dadosXls .= "          <th>PGTO ADESÃO</th>";
    $dadosXls .= "          <th>DATA IMP. BOLETO</th>";
    $dadosXls .= "          <th>DATA DO AGENDAMENTO</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['CLIENTE'])."</td>";
        $dadosXls .= "          <td>".$res['CPF']."</td>";
        $dadosXls .= "          <td>".$res['TELEFONE1']."</td>";
        $dadosXls .= "          <td>".$res['TELEFONE2']."</td>";
        $dadosXls .= "          <td>".$res['CELULAR']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['STATUS'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['TIPO_COBRANCA'])."</td>";
        $dadosXls .= "          <td>".$res['RECORRENTE']."</td>";
        $dadosXls .= "          <td>".$res['ACEITE']."</td>";
		$dadosXls .= "          <td>".$res['DATA_ACEITE']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['GRUPO'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['SUBGRUPO'])."</td>";
        $dadosXls .= "          <td>".$res['TIPOVENDA']."</td>";
        $dadosXls .= "          <td>".$res['SUPERVISOR']."</td>";
        $dadosXls .= "          <td>".$res['VENDEDOR']."</td>";
	    $dadosXls .= "          <td>".$res['ORIGEM_VENDA']."</td>";
        $dadosXls .= "          <td>".$res['DATA_VENDA']."</td>";
        $dadosXls .= "          <td>".$res['DATA_ADESÃO']."</td>";
        $dadosXls .= "          <td>".$res['DATA_PAGAMENTO']."</td>";
        $dadosXls .= "          <td>".$res['DATA_IMPRESSAO']."</td>";
        $dadosXls .= "          <td>".$res['DATA_AGENDAMENTO']."</td>";
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