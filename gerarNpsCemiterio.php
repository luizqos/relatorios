<?php
header('Content-Type: text/html; charset=UTF-8');
// Verificador de sessão 
//require "verifica.php"; 
$tiraacento = array(
  'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj',''=>'Z', ''=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
  'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
  'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
  'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
  'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
  'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
  'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
  'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
);
// Conexão com o banco de dados 
//require "comum.php"; 

date_default_timezone_set('America/Fortaleza');
$datainicio = date('Y-m-d', strtotime('-13days'));
$datafim = date('Y-m-d', strtotime('-7days'));

echo "INICIO: ".$datainicio.'</br>'."FIM: ".$datafim."</br>";

$diaSemana = date('D');
//echo "inicio:" .$datainicio;
//echo " - fim:" .$datafim;

        if ( $diaSemana == 'Mon'){
          $dataEnvio = date('Y-m-d');
          $res_query = "--TRANSLADO
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
                     then dbo.TiraLetras(Translados.Celular)
                     when dbo.TiraLetras(Translados.Telefone) is not null 
                     and dbo.TiraLetras(Translados.Telefone) <> ' '
                     and Translados.Telefone not like '0%'
                     and len(dbo.tiraletras(Translados.Telefone)) >= 9 
                     and (substring(dbo.tiraletras(Translados.Telefone), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(Translados.Telefone), 1, 1) in('9', '8', '7')
                       )
                     --then substring(dbo.TiraLetras(telefone), 3,9)
                     then dbo.tiraletras(Translados.Telefone)
                     when dbo.TiraLetras(Translados.Telefone) is not null 
                     and dbo.TiraLetras(Translados.Telefone) <> ' '
                     and Translados.Telefone not like '0%'
                     and len(dbo.tiraletras(Translados.Telefone)) >= 9 
                     and (substring(dbo.tiraletras(Translados.Telefone), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(Translados.Telefone), 1, 1) in('9', '8', '7')
                       )
                     then dbo.TiraLetras(Translados.Telefone)
                     --then substring(dbo.TiraLetras(telefone2), 3,9)
                   end AS 'CELULAR', ISNULL(Cidades.Nome,'') AS 'CIDADE',  ISNULL(Cidades.UF,'') AS 'UF', Translados.INSCRICAO, Grupos.descricao FUNERARIA,  Associados.GRUPO,'4 - TRANSLADO' SERVIÇO, Associados.Filial AS CEMITERIO 
           FROM Translados 
           LEFT JOIN Associados ON Associados.Inscricao = Translados.Inscricao
           INNER JOIN grupos ON associados.grupo = grupos.grupo
           LEFT JOIN Cidades ON Cidades.Codigo = Translados.Cidade
           WHERE Translados.DataTranslados BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.000' 
           AND Associados.Status IN (1,2)

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
                     then dbo.TiraLetras(Exumacao.Celular)
                     when dbo.TiraLetras(Exumacao.Telefone) is not null 
                     and dbo.TiraLetras(Exumacao.Telefone) <> ' '
                     and Exumacao.Telefone not like '0%'
                     and len(dbo.tiraletras(Exumacao.Telefone)) >= 9 
                     and (substring(dbo.tiraletras(Exumacao.Telefone), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(Exumacao.Telefone), 1, 1) in('9', '8', '7')
                       )
                     --then substring(dbo.TiraLetras(telefone), 3,9)
                     then dbo.tiraletras(Exumacao.Telefone)
                     when dbo.TiraLetras(Exumacao.Telefone) is not null 
                     and dbo.TiraLetras(Exumacao.Telefone) <> ' '
                     and Exumacao.Telefone not like '0%'
                     and len(dbo.tiraletras(Exumacao.Telefone)) >= 9 
                     and (substring(dbo.tiraletras(Exumacao.Telefone), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(Exumacao.Telefone), 1, 1) in('9', '8', '7')
                       )
                     then dbo.TiraLetras(Exumacao.Telefone)
                     --then substring(dbo.TiraLetras(telefone2), 3,9)
                   end AS 'Celular', ISNULL(Cidades.Nome,'') AS Cidade,  ISNULL(Cidades.UF,'') AS 'UF', Exumacao.Inscricao, Grupos.descricao FUNERARIA,  Associados.Grupo, '3 - EXUMAÇÃO' SERVIÇO, Associados.Filial AS CEMITERIO
           FROM Exumacao 
           LEFT JOIN Associados ON Associados.Inscricao = Exumacao.Inscricao
           INNER JOIN grupos ON associados.grupo = grupos.grupo
           LEFT JOIN Cidades ON Cidades.Codigo = Exumacao.Cidade
           WHERE Exumacao.DataExumacao BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
           AND Associados.Status IN (1,2)

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
                     then dbo.TiraLetras(CelularDeclarante)
                     when dbo.TiraLetras(TelefoneDeclarante) is not null 
                     and dbo.TiraLetras(TelefoneDeclarante) <> ' '
                     and TelefoneDeclarante not like '0%'
                     and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
                     and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
                       )
                     --then substring(dbo.TiraLetras(telefone), 3,9)
                     then dbo.tiraletras(TelefoneDeclarante)
                     when dbo.TiraLetras(TelefoneDeclarante) is not null 
                     and dbo.TiraLetras(TelefoneDeclarante) <> ' '
                     and Telefone not like '0%'
                     and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
                     and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
                       )
                     then dbo.TiraLetras(TelefoneDeclarante)
                     --then substring(dbo.TiraLetras(telefone2), 3,9)
                   end AS 'Celular',  Cidades.nome CIDADE, Cidades.uf UF, Associados.inscricao INSCRICAO, Grupos.descricao FUNERARIA,  Associados.Grupo, '1 - SEPULTAMENTO' SERVIÇO, Associados.Filial AS CEMITERIO 
           FROM obitos
           LEFT JOIN Associados ON Associados.Inscricao = Obitos.Inscricao
           INNER JOIN cidades ON associados.cidade = cidades.codigo
           INNER JOIN grupos ON associados.grupo = grupos.grupo
           WHERE Sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
           AND Associados.Status IN (1,2)

           UNION ALL
           --SEPULTAMENTO NPS
           SELECT associados.NOME, 
             CELULAR = case
                     when dbo.TiraLetras(Celular) is not null 
                     and dbo.TiraLetras(Celular) <> ' ' 
                     and Celular not like '0%'
                     and len(dbo.tiraletras(Celular)) >= 9  
                     and (substring(dbo.tiraletras(Celular), 3, 1) in('9', '8', '7') 
                         OR substring(dbo.tiraletras(Celular), 1, 1) in('9', '8', '7')
                       )
                     --then substring(dbo.TiraLetras(celular), 3,9)
                     then dbo.TiraLetras(Celular)
                     when dbo.TiraLetras(Telefone2) is not null 
                     and dbo.TiraLetras(Telefone2) <> ' '
                     and Telefone2 not like '0%'
                     and len(dbo.tiraletras(Telefone2)) >= 9 
                     and (substring(dbo.tiraletras(Telefone2), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(Telefone2), 1, 1) in('9', '8', '7')
                       )
                     --then substring(dbo.TiraLetras(telefone), 3,9)
                     then dbo.tiraletras(Telefone2)
                     when dbo.TiraLetras(Telefone) is not null 
                     and dbo.TiraLetras(Telefone) <> ' '
                     and Telefone not like '0%'
                     and len(dbo.tiraletras(Telefone)) >= 9 
                     and (substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(Telefone), 1, 1) in('9', '8', '7')
                       )
                     then dbo.TiraLetras(Telefone)
                     --then substring(dbo.TiraLetras(telefone2), 3,9)
                   end, 
           Cidades.nome CIDADE, 
           Cidades.uf UF, 
           Associados.inscricao INSCRICAO, 
           Grupos.descricao FUNERARIA,  Associados.Grupo,
           '5 - SEPULTAMENTONPS' SERVIÇO, Associados.Filial AS CEMITERIO 
           FROM associados
           INNER JOIN cidades ON associados.cidade = cidades.codigo
           INNER JOIN grupos ON associados.grupo = grupos.grupo
           WHERE associados.status in(1,2) and 
           associados.Inscricao in (select inscricao from obitos where sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')

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
                     then dbo.TiraLetras(CelularDeclarante)
                     when dbo.TiraLetras(TelefoneDeclarante) is not null 
                     and dbo.TiraLetras(TelefoneDeclarante) <> ' '
                     and TelefoneDeclarante not like '0%'
                     and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
                     and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
                       )
                     --then substring(dbo.TiraLetras(telefone), 3,9)
                     then dbo.tiraletras(TelefoneDeclarante)
                     when dbo.TiraLetras(TelefoneDeclarante) is not null 
                     and dbo.TiraLetras(TelefoneDeclarante) <> ' '
                     and TelefoneDeclarante not like '0%'
                     and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
                     and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
                         OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
                       )
                     then dbo.TiraLetras(TelefoneDeclarante)
                     --then substring(dbo.TiraLetras(telefone2), 3,9)
                   end AS 'Celular', Cidades.nome CIDADE, Cidades.uf UF, Associados.inscricao INSCRICAO, Grupos.descricao FUNERARIA,  Associados.Grupo, '2 - CREMAÇÃO' SERVIÇO, Associados.Filial AS CEMITERIO 
           FROM obitos
           LEFT JOIN Associados ON Associados.Inscricao = Obitos.Inscricao
           INNER JOIN cidades ON associados.cidade = cidades.codigo
           INNER JOIN grupos ON associados.grupo = grupos.grupo
           WHERE Obitos.Datacremacao between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
           AND Associados.Status IN (1,2)

          ";
          require_once "conn/conexao_mssql_cemiterio.php";
          
          
          try{
           
              $Conexao    = Conexao::getConnection();
              $query      = $Conexao->query("$res_query");
              $resultado   = $query->fetchAll();
           }catch(Exception $e){
           
              echo $e->getMessage();
              exit;
          
           }	
           //echo var_dump($resultado);
           //echo $banco;

       //echo var_dump($resultado);
       
       echo"<table border=1>";
       echo"<tr>";
       echo"<th>Nome</th>";
       echo"<th>Inscricao</th>";
       echo"<th>Celular</th>";
       echo"<th>Cidade</th>";
       echo"<th>UF</th>";
       echo"<th>Servico</th>";
       echo"<th>Cemiterio</th>";
       echo"</tr>";
       
         foreach($resultado as $r) {
          
          $inscricao = $r['INSCRICAO'];
          $nome = strtr($r['NOME'], $tiraacento);
          $telefone = preg_replace('/[^0-9]/', '', $r['CELULAR']);
          $cidade = strtr($r['CIDADE'], $tiraacento);
          $uf = $r['UF'];
          $servico = strtr($r['SERVIÇO'], $tiraacento);
          $cemiterio = $r['CEMITERIO'];

            if (strlen($telefone) == 11 && $cemiterio <= 5)
            {
              
              echo "<tr>";
              echo "<td>$nome</td>";
              echo "<td>$inscricao</td>";
              echo "<td>$telefone</td>";
              echo "<td>$cidade</td>";
              echo "<td>$uf</td>";
              echo "<td>$servico</td>";
              echo "<td>$cemiterio</td>";
              echo "</tr>";
              

              $serverName = "gold.grupozelo.net";
              $connectionInfo = array( "Database"=>"Zelo", "UID"=>"sa", "PWD"=>"071999gs" );
              $conn = sqlsrv_connect( $serverName, $connectionInfo);
              if( $conn === false ) {
                  die( print_r( sqlsrv_errors(), true));
              }
              $sql = "select count(*) Qtde from NPS_Cemiterios where Inscricao = $inscricao and Telefone = '$telefone' and DataEnvio = '$dataEnvio' and Servico = '$servico' and cemiterio = '$cemiterio' ";
              $stmt = sqlsrv_query( $conn, $sql );
              if( $stmt === false) {
                  die( print_r( sqlsrv_errors(), true) );
              }

              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                //echo $row['Qtde']."<br />";
  
                $qtdeLinhas = $row['Qtde'];
                if ($qtdeLinhas == 0)
                {
                  $sql = "INSERT INTO NPS_Cemiterios (Nome, Telefone, Inscricao, Cidade, UF, Cemiterio, Servico, DataEnvio) VALUES (?,?,?,?,?,?,?,?)";
                  $params = array($nome, $telefone, $inscricao, $cidade, $uf, $cemiterio, $servico, $dataEnvio);
                  $stmt = sqlsrv_query( $conn, $sql, $params);
                  if( $stmt === false ) {
                      die( print_r( sqlsrv_errors(), true));
                    }
                }
                else
                {
                  //echo "Duplicou... <br />";
                }
          }
          
        } 
  }
  echo"</table>";
}
?>
