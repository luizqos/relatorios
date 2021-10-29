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
$inicioPeriodo = date('Y-m-d', strtotime('-9days'));
$fimPeriodo = date('Y-m-d', strtotime('-3days'));
$diaSemana = date('D');
//echo "inicio:" .$inicioPeriodo;
//echo " - fim:" .$fimPeriodo;

        if ( $diaSemana == 'Wed'){
          $dataEnvio = date('Y-m-d');

        $res_query = "SELECT 
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
        UPPER(CidadesIBGE.nome) CIDADE, 
        UfIBGE.SIGLA UF, 
        Associados.inscricao INSCRICAO, 
        Grupos.descricao FUNERARIA
      FROM associados
      INNER JOIN cidades ON Associados.cidade = Cidades.codigo
      INNER JOIN CidadesIBGE ON Cidades.CodigoIBGE = CidadesIBGE.ID
      INNER JOIN UfIBGE ON CidadesIBGE.IDUF = UfIBGE.CODIGO
      INNER JOIN grupos ON Associados.grupo = Grupos.grupo
      INNER JOIN Inscricao ON Associados.Inscricao = Inscricao.Inscricao
      WHERE Associados.data between '$inicioPeriodo 00:00:00.000' and '$fimPeriodo 23:59:59.000'
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
      ORDER BY Associados.nome
        ";
        
        require_once "conn/conexao_mssql_zelo.php";
        
        try{
         
            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("$res_query");
            $resultado   = $query->fetchAll();
         }catch(Exception $e){
         
            echo $e->getMessage();
            exit;
        
         }	
        
         foreach($resultado as $r) {
          
          $inscricao = $r['INSCRICAO'];
          $nome = strtr($r['NOME'], $tiraacento);
          $telefone = $r['CELULAR']; 
          $cidade = strtr($r['CIDADE'], $tiraacento);
          $uf = $r['UF'];
          $funeraria = strtr($r['FUNERARIA'], $tiraacento);
        
              $serverName = "gold.grupozelo.net";
              $connectionInfo = array( "Database"=>"Zelo", "UID"=>"sa", "PWD"=>"071999gs" );
              $conn = sqlsrv_connect( $serverName, $connectionInfo);
              if( $conn === false ) {
                  die( print_r( sqlsrv_errors(), true));
              }
        
              //valida se ja existe o regisrto
                $sql = "select count(*) Qtde from NPS_Vendas where Inscricao = $inscricao and Telefone = '$telefone' and DataEnvio = '$dataEnvio'";
                $stmt = sqlsrv_query( $conn, $sql );
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                }
        
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                      //echo $row['Qtde']."<br />";
        
                      $qtdeLinhas = $row['Qtde'];
                      if ($qtdeLinhas == 0)
                      {
                        $sql = "INSERT INTO NPS_Vendas (Nome, Telefone, Inscricao, Cidade, UF, Funeraria, DataEnvio) VALUES (?,?,?,?,?,?,?)";
                        $params = array($nome ,$telefone, $inscricao, $cidade, $uf, $funeraria, $dataEnvio);
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
        //Caso Haja duplicidade de Telefone os registros duplicados serão deletados
        $sql = "delete from NPS_Vendas where telefone in 
                                                                      (
                                                                      select Telefone
                                                                      from NPS_Vendas
                                                                      where  DataEnvio = '$dataEnvio 00:00:00.000'
                                                                      group by Telefone
                                                                      having count(*) > 1
                                                                      )
                 and DataEnvio = '$dataEnvio 00:00:00.000' or len(Telefone)>11";
        $stmt = sqlsrv_query( $conn, $sql );
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }
        sqlsrv_free_stmt( $stmt);
  }
?>
