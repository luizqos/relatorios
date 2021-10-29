<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

$contratos  = $_GET['contratos'];
require_once "conn/conexao_mssql_zelo.php";

try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("
                                    SELECT A.Inscricao, A.Grupo, A.SubGrupo, A.TipoVenda, A.Nome, A.AssTipoCobranca AS TipoCobranca, A.TipoAceite, CONVERT(varchar(10),A.DataAceite, 103) AS DataAceite, C.Nome AS Cidade 
                                    FROM ASSOCIADOS AS A
                                    INNER JOIN Cidades AS C
                                    ON A.Cidade = C.Codigo
                                    WHERE INSCRICAO IN (
                                        $contratos
                                    )
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
    $arquivo = 'CONTRATOS.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>INSCRIÇÃO</th>";
    $dadosXls .= "          <th>GRUPO</th>";
    $dadosXls .= "          <th>SUBGRUPO</th>";
    $dadosXls .= "          <th>TIPO VENDA</th>";
    $dadosXls .= "          <th>NOME</th>";
    $dadosXls .= "          <th>CIDADE</th>";
    $dadosXls .= "          <th>COBRANÇA</th>";
    $dadosXls .= "          <th>TIPO ACEITE</th>";
    $dadosXls .= "          <th>DATA ACEITE</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['Inscricao']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['Grupo'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['SubGrupo'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['TipoVenda'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['Nome'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['Cidade'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['TipoCobranca'])."</td>";
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