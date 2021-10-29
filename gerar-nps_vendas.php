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
Associados.NOME NOME, 
CELULAR = case
        when dbo.TiraLetras(Celular) is not null 
        and dbo.TiraLetras(Celular) <> ' ' 
        and Celular not like '0%'
        and len(dbo.tiraletras(Celular)) >= 9  
        and (substring(dbo.tiraletras(Celular), 3, 1) in('9', '8', '7') 
            OR substring(dbo.tiraletras(Celular), 1, 1) in('9', '8', '7')
          )
        --then substring(dbo.TiraLetras(celular), 3,9)
        then IIF(LEN(dbo.TiraLetras(Celular)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Celular),1,2),'9',SUBSTRING(dbo.TiraLetras(Celular),3,12)), dbo.TiraLetras(Celular))
        when dbo.TiraLetras(Telefone2) is not null 
        and dbo.TiraLetras(Telefone2) <> ' '
        and Telefone2 not like '0%'
        and len(dbo.tiraletras(Telefone2)) >= 9 
        and (substring(dbo.tiraletras(Telefone2), 3, 1) in('9', '8', '7')
            OR substring(dbo.tiraletras(Telefone2), 1, 1) in('9', '8', '7')
          )
        --then substring(dbo.TiraLetras(telefone), 3,9)
        --then dbo.tiraletras(Telefone2)
        then IIF(LEN(dbo.TiraLetras(Telefone2)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Telefone2),1,2),'9',SUBSTRING(dbo.TiraLetras(Telefone2),3,12)), dbo.TiraLetras(Telefone2))
        when dbo.TiraLetras(Telefone) is not null 
        and dbo.TiraLetras(Telefone) <> ' '
        and Telefone not like '0%'
        and len(dbo.tiraletras(Telefone)) >= 9 
        and (substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
            OR substring(dbo.tiraletras(Telefone), 1, 1) in('9', '8', '7')
          )
        --then dbo.TiraLetras(Telefone)
        then IIF(LEN(dbo.TiraLetras(Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(Telefone),3,12)), dbo.TiraLetras(Telefone))
        --then substring(dbo.TiraLetras(telefone2), 3,9)
      end,
Cidades.nome CIDADE, 
Cidades.uf UF, 
Associados.inscricao INSCRICAO, 
Grupos.descricao FUNERARIA
FROM associados
INNER JOIN cidades ON Associados.cidade = Cidades.codigo
INNER JOIN grupos ON Associados.grupo = Grupos.grupo
INNER JOIN Inscricao ON Associados.Inscricao = Inscricao.Inscricao
WHERE Associados.data between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
AND Inscricao.Pagamento IS NOT NULL
AND Inscricao.ValorPago IS NOT NULL
AND (Celular not like '0%' 
and dbo.TiraLetras(Celular) is not null 
and dbo.TiraLetras(Celular) <> ' ' 
and substring(dbo.tiraletras(Celular), 3, 1) in('9', '8', '7')
and LEN(dbo.TiraLetras(Celular)) >= 9 and LEN(dbo.TiraLetras(Celular)) <= 11
OR 
Telefone2 not like '0%'
and dbo.TiraLetras(Telefone2) is not null 
and dbo.TiraLetras(Telefone2) <> ' '
and substring(dbo.tiraletras(Telefone2), 3, 1) in('9', '8', '7')
and LEN(dbo.TiraLetras(Telefone2)) >= 9 and LEN(dbo.TiraLetras(Telefone2)) <= 11
OR 
Telefone not like '0%'
and dbo.TiraLetras(Telefone) is not null 
and dbo.TiraLetras(Telefone) <> ' '
and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
and LEN(dbo.TiraLetras(Telefone)) >= 9 and LEN(dbo.TiraLetras(telefone)) <= 11
)
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
		$arquivo = 'NPS_VENDAS_'.$datainicio.'a'.$datafim.'.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>NOME</th>";
    $dadosXls .= "          <th>CELULAR</th>";
    $dadosXls .= "          <th>CIDADE</th>";
	$dadosXls .= "          <th>UF</th>";
	$dadosXls .= "          <th>INSCRIÇÃO</th>";
	$dadosXls .= "          <th>FUNERARIA</th>";	
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
		$dadosXls .= "          <td>".$res['CELULAR']."</td>";
		$dadosXls .= "          <td>".strtoupper($res['CIDADE'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['UF'])."</td>";
        $dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
		$dadosXls .= "          <td>".strtoupper($res['FUNERARIA'])."</td>";
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