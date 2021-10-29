<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

//Verificação de chamada direta
$id_relatorio = 9;
require "chamada.php"; 

$datainicio  = $_GET['datai'];
$datafim  = $_GET['dataf'];
require_once "conn/conexao_mssql_zelo.php";

try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("SELECT FunerObito.Lancamento LANCAMENTO, 
    FunerPed.Falecido FALECIDO, 
    Funerarias.nome FUNERARIA, 
    FunerGrupoObito.Descricao LABORATORIO, 
    CONVERT(VARCHAR(10), FunerPed.DataFalecimento, 103) AS DATA_FALECIMENTO, 
    FunerObito.CIDADE_F CIDADE_FALECIMENTO,
    FunerPed.numOrdem ORDEM_SERVICO, 
    CONVERT(VARCHAR(10), FunerPed.Data, 103) AS DATA_OS,
    FunerPed.Obito TIPO_OBITO, 
    FunerPedItens.Codigo CODIGO, 
    FunerProdutos.Descricao DESCRICAO_PRODUTO,
    FunerPedItens.Qtde QUANTIDADE, 
    FunerPedItens.ValorUnit VALOR_UNITARIO, 
    FunerPedItens.ValorTot VALOR_TOTAL
FROM 
 FunerPed
 left outer join FunerObito ON FunerPed.NumObito = funerobito.lancamento and funerped.funeraria = funerobito.funeraria
 inner join funerarias ON funerPed.funeraria = funerarias.funeraria
 left outer join FunerGrupoObito ON FunerGrupoObito.codigo = funerped.codigoFunerLaboratorio
 left outer join funerPedItens ON FunerPedItens.funeraria = funerped.funeraria and funerpeditens.numordem = funerped.NumOrdem 
 inner join FunerProdutos ON FunerProdutos.codigo = funerpeditens.codigo and funerprodutos.funeraria = funerpeditens.funeraria
WHERE
 FunerPed.DataFalecimento BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.999' and
 FunerProdutos.Grupo = 1 and FunerProdutos.SubGrupo in (2,3,4,27,31)
ORDER BY 
 FunerPed.Funeraria, FunerPed.Numordem");
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
		$arquivo = 'UTILIZAÇÃO DE URNAS.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>LANÇAMENTO</th>";
    $dadosXls .= "          <th>FALECIDO</th>";
    $dadosXls .= "          <th>FUNERARIA</th>";
    $dadosXls .= "          <th>LABORATORIO</th>";	
    $dadosXls .= "          <th>DATA DO FALECIMENTO</th>";
    $dadosXls .= "          <th>CIDADE DO FALECIMENTO</th>";
    $dadosXls .= "          <th>ORDEM DE SERVIÇO</th>";
	$dadosXls .= "          <th>DATA OS</th>";
    $dadosXls .= "          <th>TIPO DE OBITO</th>";
    $dadosXls .= "          <th>CÓDIGO</th>";
    $dadosXls .= "          <th>DESCRIÇÃO DO PRODUTO</th>";
    $dadosXls .= "          <th>QUANTIDADE</th>";
    $dadosXls .= "          <th>VALOR UNITÁRIO</th>";
    $dadosXls .= "         <th>VALOR TOTAL</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['LANCAMENTO']."</td>";
        $dadosXls .= "          <td>".$res['FALECIDO']."</td>";
        $dadosXls .= "          <td>".$res['FUNERARIA']."</td>";
        $dadosXls .= "          <td>".$res['LABORATORIO']."</td>";
        $dadosXls .= "          <td>".$res['DATA_FALECIMENTO']."</td>";
        $dadosXls .= "          <td>".$res['CIDADE_FALECIMENTO']."</td>";
        $dadosXls .= "          <td>".$res['ORDEM_SERVICO']."</td>";
        $dadosXls .= "          <td>".$res['DATA_OS']."</td>";
        $dadosXls .= "          <td>".$res['TIPO_OBITO']."</td>";
        $dadosXls .= "          <td>".$res['CODIGO']."</td>";
        $dadosXls .= "          <td>".$res['DESCRICAO_PRODUTO']."</td>";
        $dadosXls .= "          <td>".number_format($res['QUANTIDADE'], 0,',','.')."</td>";
        $dadosXls .= "          <td>".number_format($res['VALOR_UNITARIO'], 2,',','.')."</td>";
        $dadosXls .= "          <td>".number_format($res['VALOR_TOTAL'], 2,',','.')."</td>";
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