<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

$cemiterio = $_GET ['cemiterio'];
$datainicio  = $_GET['datai'];
$datafim  = $_GET['dataf'];

if ($cemiterio == 2)
{
	$ddd = '21';
}
if ($cemiterio == 4)
{
	$ddd = '63';
}
if ($cemiterio == 5)
{
	$ddd = '11';
}
if ($cemiterio == 1 || $cemiterio == 3)
{
	$ddd = '31';
}


require_once "conn/conexao_mssql_cemiterio.php";

try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("  --ducash
   select 'DUCASH' NOME, '31992972242' CELULAR, 'BELO HORIZONTE' CIDADE, 'MG' UF, '1234144'INSCRICAO,  '-' GRUPO, 'DUCASH' SERVIÇO
  UNION ALL
  select 'DUCASH' NOME, '31984128731' CELULAR, 'BELO HORIZONTE' CIDADE, 'MG' UF, '1234144'INSCRICAO,  '-' GRUPO, 'DUCASH' SERVIÇO
  UNION ALL
  select 'DUCASH' NOME, '31999992848 ' CELULAR, 'BELO HORIZONTE' CIDADE, 'MG' UF, '1234144'INSCRICAO, '-' GRUPO, 'DUCASH' SERVIÇO
   UNION ALL
   --TRANSLADO
	SELECT DISTINCT Translados.Requerente AS 'NOME',
					   case
					   when dbo.TiraLetras(Translados.Celular) is not null 
					   and dbo.TiraLetras(Translados.Celular) <> ' ' 
					   and Translados.Celular not like '0%'
					   and len(dbo.tiraletras(Translados.Celular)) >= 9  
					   and (substring(dbo.tiraletras(Translados.Celular), 3, 1) in('9', '8', '7') 
							   OR substring(dbo.tiraletras(Translados.Celular), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(celular), 3,9)
					   --then dbo.TiraLetras(Translados.Celular)
					   then IIF(LEN(dbo.TiraLetras(Translados.Celular)) = 9, CONCAT($ddd,dbo.TiraLetras(Translados.Celular)), IIF(LEN(dbo.TiraLetras(Translados.Celular)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Translados.Celular),1,2),'9',SUBSTRING(dbo.TiraLetras(Translados.Celular),3,12)), dbo.TiraLetras(Translados.Celular)))
					   when dbo.TiraLetras(Translados.Telefone) is not null 
					   and dbo.TiraLetras(Translados.Telefone) <> ' '
					   and Translados.Telefone not like '0%'
					   and len(dbo.tiraletras(Translados.Telefone)) >= 9 
					   and (substring(dbo.tiraletras(Translados.Telefone), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(Translados.Telefone), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(telefone), 3,9)
					   then IIF(LEN(dbo.TiraLetras(Translados.Telefone)) = 9, CONCAT($ddd,dbo.TiraLetras(Translados.Telefone)), IIF(LEN(dbo.TiraLetras(Translados.Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Translados.Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(Translados.Telefone),3,12)), dbo.TiraLetras(Translados.Telefone)))
					   when dbo.TiraLetras(Translados.Telefone) is not null 
					   and dbo.TiraLetras(Translados.Telefone) <> ' '
					   and Translados.Telefone not like '0%'
					   and len(dbo.tiraletras(Translados.Telefone)) >= 9 
					   and (substring(dbo.tiraletras(Translados.Telefone), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(Translados.Telefone), 1, 1) in('9', '8', '7')
						   )
					   then IIF(LEN(dbo.TiraLetras(Translados.Telefone)) = 9, CONCAT($ddd,dbo.TiraLetras(Translados.Telefone)), IIF(LEN(dbo.TiraLetras(Translados.Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Translados.Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(Translados.Telefone),3,12)), dbo.TiraLetras(Translados.Telefone)))
					   --then substring(dbo.TiraLetras(telefone2), 3,9)
				   end AS 'CELULAR', ISNULL(Cidades.Nome,'') AS 'CIDADE',  ISNULL(Cidades.UF,'') AS 'UF', Translados.INSCRICAO,  Associados.GRUPO,'TRANSLADO' SERVIÇO 
   FROM Translados 
   LEFT JOIN Associados ON Associados.Inscricao = Translados.Inscricao
   INNER JOIN grupos ON associados.grupo = grupos.grupo
   LEFT JOIN Cidades ON Cidades.Codigo = Translados.Cidade
   WHERE Translados.DataTranslados BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.000' 
   AND Associados.Status IN (1,2)
   AND LEN(Translados.Requerente)>3
   AND Associados.Filial = ('$cemiterio')
   UNION ALL
   --EXUMAÇÃO
	SELECT DISTINCT Exumacao.Requerente AS 'Nome',
					   case
					   when dbo.TiraLetras(Exumacao.Celular) is not null 
					   and dbo.TiraLetras(Exumacao.Celular) <> ' ' 
					   and Exumacao.Celular not like '0%'
					   and len(dbo.tiraletras(Exumacao.Celular)) >= 9  
					   and (substring(dbo.tiraletras(Exumacao.Celular), 3, 1) in('9', '8', '7') 
							   OR substring(dbo.tiraletras(Exumacao.Celular), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(celular), 3,9)
					   --then dbo.TiraLetras(Exumacao.Celular)
					   then IIF(LEN(dbo.TiraLetras(Exumacao.Celular)) = 9, CONCAT($ddd,dbo.TiraLetras(Exumacao.Celular)), IIF(LEN(dbo.TiraLetras(Exumacao.Celular)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Exumacao.Celular),1,2),'9',SUBSTRING(dbo.TiraLetras(Exumacao.Celular),3,12)), dbo.TiraLetras(Exumacao.Celular)))
					   when dbo.TiraLetras(Exumacao.Telefone) is not null 
					   and dbo.TiraLetras(Exumacao.Telefone) <> ' '
					   and Exumacao.Telefone not like '0%'
					   and len(dbo.tiraletras(Exumacao.Telefone)) >= 9 
					   and (substring(dbo.tiraletras(Exumacao.Telefone), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(Exumacao.Telefone), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(telefone), 3,9)
					   --then dbo.tiraletras(Exumacao.Telefone)
					   then IIF(LEN(dbo.TiraLetras(Exumacao.Telefone)) = 9, CONCAT($ddd,dbo.TiraLetras(Exumacao.Telefone)), IIF(LEN(dbo.TiraLetras(Exumacao.Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Exumacao.Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(Exumacao.Telefone),3,12)), dbo.TiraLetras(Exumacao.Telefone)))
					   when dbo.TiraLetras(Exumacao.Telefone) is not null 
					   and dbo.TiraLetras(Exumacao.Telefone) <> ' '
					   and Exumacao.Telefone not like '0%'
					   and len(dbo.tiraletras(Exumacao.Telefone)) >= 9 
					   and (substring(dbo.tiraletras(Exumacao.Telefone), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(Exumacao.Telefone), 1, 1) in('9', '8', '7')
						   )
					   then IIF(LEN(dbo.TiraLetras(Exumacao.Telefone)) = 9, CONCAT($ddd,dbo.TiraLetras(Exumacao.Telefone)), IIF(LEN(dbo.TiraLetras(Exumacao.Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Exumacao.Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(Exumacao.Telefone),3,12)), dbo.TiraLetras(Exumacao.Telefone)))
					   --then substring(dbo.TiraLetras(telefone2), 3,9)
				   end AS 'Celular', ISNULL(Cidades.Nome,'') AS Cidade,  ISNULL(Cidades.UF,'') AS 'UF', Exumacao.Inscricao,  Associados.Grupo, 'EXUMAÇÃO' SERVIÇO
   FROM Exumacao 
   LEFT JOIN Associados ON Associados.Inscricao = Exumacao.Inscricao
   INNER JOIN grupos ON associados.grupo = grupos.grupo
   LEFT JOIN Cidades ON Cidades.Codigo = Exumacao.Cidade
   WHERE Exumacao.DataExumacao BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
   AND Associados.Status IN (1,2)
   AND LEN(Exumacao.Requerente)>3
   AND Associados.Filial = ('$cemiterio')
   UNION ALL
   --SEPULTAMENTO
   select DISTINCT Obitos.NomeDeclarante AS 'Nome',
					   case
					   when dbo.TiraLetras(CelularDeclarante) is not null 
					   and dbo.TiraLetras(CelularDeclarante) <> ' ' 
					   and CelularDeclarante not like '0%'
					   and len(dbo.tiraletras(CelularDeclarante)) >= 9  
					   and (substring(dbo.tiraletras(CelularDeclarante), 3, 1) in('9', '8', '7') 
							   OR substring(dbo.tiraletras(CelularDeclarante), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(celular), 3,9)
					   --then dbo.TiraLetras(CelularDeclarante)
					   then IIF(LEN(dbo.TiraLetras(CelularDeclarante)) = 9, CONCAT($ddd,dbo.TiraLetras(CelularDeclarante)), IIF(LEN(dbo.TiraLetras(CelularDeclarante)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(CelularDeclarante),1,2),'9',SUBSTRING(dbo.TiraLetras(CelularDeclarante),3,12)), dbo.TiraLetras(CelularDeclarante)))
					   when dbo.TiraLetras(TelefoneDeclarante) is not null 
					   and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					   and TelefoneDeclarante not like '0%'
					   and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					   and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(telefone), 3,9)
					   --then dbo.tiraletras(TelefoneDeclarante)
					   then IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 9, CONCAT($ddd,dbo.TiraLetras(TelefoneDeclarante)), IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),1,2),'9',SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),3,12)), dbo.TiraLetras(TelefoneDeclarante)))
					   when dbo.TiraLetras(TelefoneDeclarante) is not null 
					   and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					   and Telefone not like '0%'
					   and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					   and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						   )
					   --then dbo.TiraLetras(TelefoneDeclarante)
					   then IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 9, CONCAT($ddd,dbo.TiraLetras(TelefoneDeclarante)), IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),1,2),'9',SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),3,12)), dbo.TiraLetras(TelefoneDeclarante)))
					   --then substring(dbo.TiraLetras(telefone2), 3,9)
				   end AS 'Celular',  Cidades.nome CIDADE, Cidades.uf UF, Associados.inscricao INSCRICAO, Associados.Grupo, 'SEPULTAMENTO' SERVIÇO 
   FROM obitos
   LEFT JOIN Associados ON Associados.Inscricao = Obitos.Inscricao
   INNER JOIN cidades ON associados.cidade = cidades.codigo
   INNER JOIN grupos ON associados.grupo = grupos.grupo
   WHERE Sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
   AND Associados.Status IN (1,2)
   AND LEN(Obitos.NomeDeclarante)>3
   AND Associados.Filial = ('$cemiterio')
   UNION ALL
   --SEPULTAMENTO NPS
   SELECT DISTINCT associados.NOME, 
	   CELULAR = case
					   when dbo.TiraLetras(Celular) is not null 
					   and dbo.TiraLetras(Celular) <> ' ' 
					   and Celular not like '0%'
					   and len(dbo.tiraletras(Celular)) >= 9  
					   and (substring(dbo.tiraletras(Celular), 3, 1) in('9', '8', '7') 
							   OR substring(dbo.tiraletras(Celular), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(celular), 3,9)
					   --then dbo.TiraLetras(Celular)
					   then IIF(LEN(dbo.TiraLetras(Celular)) = 9, CONCAT($ddd,dbo.TiraLetras(Celular)), IIF(LEN(dbo.TiraLetras(Celular)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Celular),1,2),'9',SUBSTRING(dbo.TiraLetras(Celular),3,12)), dbo.TiraLetras(Celular)))
					   when dbo.TiraLetras(Telefone2) is not null 
					   and dbo.TiraLetras(Telefone2) <> ' '
					   and Telefone2 not like '0%'
					   and len(dbo.tiraletras(Telefone2)) >= 9 
					   and (substring(dbo.tiraletras(Telefone2), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(Telefone2), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(telefone), 3,9)
					   --then dbo.tiraletras(Telefone2)
					   then IIF(LEN(dbo.TiraLetras(Telefone2)) = 9, CONCAT($ddd,dbo.TiraLetras(Telefone2)), IIF(LEN(dbo.TiraLetras(Telefone2)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Telefone2),1,2),'9',SUBSTRING(dbo.TiraLetras(Telefone2),3,12)), dbo.TiraLetras(Telefone2)))
					   when dbo.TiraLetras(Telefone) is not null 
					   and dbo.TiraLetras(Telefone) <> ' '
					   and Telefone not like '0%'
					   and len(dbo.tiraletras(Telefone)) >= 9 
					   and (substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(Telefone), 1, 1) in('9', '8', '7')
						   )
					   --then dbo.TiraLetras(Telefone)
					   then IIF(LEN(dbo.TiraLetras(Telefone)) = 9, CONCAT($ddd,dbo.TiraLetras(Telefone)), IIF(LEN(dbo.TiraLetras(Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(Telefone),3,12)), dbo.TiraLetras(Telefone)))
					   --then substring(dbo.TiraLetras(telefone2), 3,9)
				   end, 
   Cidades.nome CIDADE, 
   Cidades.uf UF, 
   Associados.inscricao INSCRICAO, 
    Associados.Grupo,
   'SEPULTAMENTO - NPS' SERVIÇO 
   FROM associados
   INNER JOIN cidades ON associados.cidade = cidades.codigo
   INNER JOIN grupos ON associados.grupo = grupos.grupo
   WHERE associados.status in(1,2) and 
   associados.Inscricao in (select inscricao from obitos where sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')
   AND LEN(associados.NOME)>3
   and associados.filial = ('$cemiterio')
   UNION ALL
   --CREMAÇÃO
   select DISTINCT Obitos.NomeDeclarante AS 'Nome', 
					   case
					   when dbo.TiraLetras(CelularDeclarante) is not null 
					   and dbo.TiraLetras(CelularDeclarante) <> ' ' 
					   and CelularDeclarante not like '0%'
					   and len(dbo.tiraletras(CelularDeclarante)) >= 9  
					   and (substring(dbo.tiraletras(CelularDeclarante), 3, 1) in('9', '8', '7') 
							   OR substring(dbo.tiraletras(CelularDeclarante), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(celular), 3,9)
					   --then dbo.TiraLetras(CelularDeclarante)
					   then IIF(LEN(dbo.TiraLetras(CelularDeclarante)) = 9, CONCAT($ddd,dbo.TiraLetras(CelularDeclarante)), IIF(LEN(dbo.TiraLetras(CelularDeclarante)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(CelularDeclarante),1,2),'9',SUBSTRING(dbo.TiraLetras(CelularDeclarante),3,12)), dbo.TiraLetras(CelularDeclarante)))
					   when dbo.TiraLetras(TelefoneDeclarante) is not null 
					   and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					   and TelefoneDeclarante not like '0%'
					   and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					   and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						   )
					   --then substring(dbo.TiraLetras(telefone), 3,9)
					   --then dbo.tiraletras(TelefoneDeclarante)
					   then IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 9, CONCAT($ddd,dbo.TiraLetras(TelefoneDeclarante)), IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),1,2),'9',SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),3,12)), dbo.TiraLetras(TelefoneDeclarante)))
					   when dbo.TiraLetras(TelefoneDeclarante) is not null 
					   and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					   and TelefoneDeclarante not like '0%'
					   and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					   and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							   OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						   )
					   --then dbo.TiraLetras(TelefoneDeclarante)
					   then IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 9, CONCAT($ddd,dbo.TiraLetras(TelefoneDeclarante)), IIF(LEN(dbo.TiraLetras(TelefoneDeclarante)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),1,2),'9',SUBSTRING(dbo.TiraLetras(TelefoneDeclarante),3,12)), dbo.TiraLetras(TelefoneDeclarante)))
					   --then substring(dbo.TiraLetras(telefone2), 3,9)
				   end AS 'Celular', Cidades.nome CIDADE, Cidades.uf UF, Associados.inscricao INSCRICAO, Associados.Grupo, 'CREMAÇÃO' SERVIÇO 
   FROM obitos
   LEFT JOIN Associados ON Associados.Inscricao = Obitos.Inscricao
   INNER JOIN cidades ON associados.cidade = cidades.codigo
   INNER JOIN grupos ON associados.grupo = grupos.grupo
   WHERE Obitos.Datacremacao between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
   AND Associados.Status IN (1,2)
   AND LEN(Obitos.NomeDeclarante)>3
   AND Associados.Filial = ('$cemiterio')");
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
		$arquivo = 'NPS_CEMITERIO.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>NOME</th>";
    $dadosXls .= "          <th>CELULAR</th>";
    $dadosXls .= "          <th>INSCRICAO</th>";
	$dadosXls .= "          <th>NOME</th>";
	$dadosXls .= "          <th>CIDADE</th>";
	$dadosXls .= "          <th>SERVIÇO</th>";	
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
		$dadosXls .= "          <td>".$res['CELULAR']."</td>";
		$dadosXls .= "          <td>".$res['INSCRICAO']."</td>";
        $dadosXls .= "          <td>".strtoupper($res['NOME'])."</td>";
        $dadosXls .= "          <td>".strtoupper($res['CIDADE'])."</td>";
		$dadosXls .= "          <td>".strtoupper($res['SERVIÇO'])."</td>";
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