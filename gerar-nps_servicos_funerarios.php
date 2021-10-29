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
    $query      = $Conexao->query("SELECT NOME = case when funerObito.DECLARAN_D <> ' ' then funerObito.DECLARAN_D when funerObito.DECLARAN_D = ' ' then 'NÃO INFORMADO' end, CELULAR = case when dbo.TiraLetras(FONE_D1) is not null and dbo.TiraLetras(FONE_D1) <> ' ' and FONE_D1 not like '0%' and len(dbo.tiraletras(FONE_D1)) >= 9 and substring(dbo.tiraletras(FONE_D1), 3, 1) in('9', '8', '7') then dbo.TiraLetras(FONE_D1) when dbo.TiraLetras(FONE_D2) is not null and dbo.TiraLetras(FONE_D2) <> ' ' and FONE_D2 not like '0%' and len(dbo.tiraletras(FONE_D2)) >= 9 and substring(dbo.tiraletras(FONE_D2), 3, 1) in('9', '8', '7') then dbo.tiraletras(FONE_D2) when dbo.TiraLetras(Telefone) is not null and dbo.TiraLetras(Telefone) <> ' ' and Telefone not like '0%' and len(dbo.tiraletras(Telefone)) >= 9 and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7') then dbo.TiraLetras(Telefone) end, funerObito.cidade CIDADE, f.nome FUNERARIA, isnull(funerObito.contrato,0) INSCRICAO, funerObito.NOME FALECIDO, DECLARANTE = case when funerObito.DECLARAN_D <> ' ' then funerObito.DECLARAN_D when funerObito.DECLARAN_D = ' ' then 'NÃO INFORMADO' end FROM funerObito INNER JOIN Funerarias f ON funerObito.funeraria = f.funeraria WHERE data between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000' and DECLARAN_D is not null and (FONE_D1 not like '0%' and dbo.TiraLetras(FONE_D1) is not null and dbo.TiraLetras(FONE_D1) <> ' ' and substring(dbo.tiraletras(FONE_D1), 3, 1) in('9', '8', '7') and LEN(dbo.TiraLetras(FONE_D1)) >= 9 and LEN(dbo.TiraLetras(FONE_D1)) <= 11 OR FONE_D2 not like '0%' and dbo.TiraLetras(FONE_D2) is not null and dbo.TiraLetras(FONE_D2) <> ' ' and substring(dbo.tiraletras(FONE_D2), 3, 1) in('9', '8', '7') and LEN(dbo.TiraLetras(FONE_D2)) >= 9 and LEN(dbo.TiraLetras(FONE_D2)) <= 11 OR Telefone not like '0%' and dbo.TiraLetras(Telefone) is not null and dbo.TiraLetras(Telefone) <> ' ' and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7') and LEN(dbo.TiraLetras(Telefone)) >= 9 and LEN(dbo.TiraLetras(telefone)) <= 11 ) ORDER BY funerObito.DECLARAN_D");
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
		$arquivo = 'NPS_SERVIÇOS_FUNERARIOS_'.$datainicio.'a'.$datafim.'.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>NOME</th>";
    $dadosXls .= "          <th>CELULAR</th>";
    $dadosXls .= "          <th>CIDADE</th>";
	$dadosXls .= "           <th>FUNERARIA</th>";
	$dadosXls .= "          <th>INSCRIÇÃO</th>";
	$dadosXls .= "          <th>FALECIDO</th>";	
	$dadosXls .= "          <th>DECLARANTE</th>";	
    $dadosXls .= "      </tr>";
    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
		$dadosXls .= "          <td>".$res['CELULAR']."</td>";
		$dadosXls .= "          <td>".strtoupper($res['CIDADE'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['FUNERARIA'])."</td>";
        $dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
		$dadosXls .= "          <td>".strtoupper($res['FALECIDO'])."</td>";
		$dadosXls .= "          <td>".strtoupper($res['DECLARANTE'])."</td>";
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