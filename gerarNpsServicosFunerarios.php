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
$inicioPeriodo = date('Y-m-d', strtotime('-14days'));
$fimPeriodo = date('Y-m-d', strtotime('-8days'));
$diaSemana = date('D');
//echo "inicio:" .$inicioPeriodo;
//echo " - fim:" .$fimPeriodo;

        if ( $diaSemana == 'Mon' ){
          $dataEnvio = date('Y-m-d');

        $res_query = "SELECT DISTINCT
        NOME = case
              when funerObito.DECLARAN_D <> ' '
              then  UPPER(funerObito.DECLARAN_D)
              when funerObito.DECLARAN_D = ' '
              then  UPPER('NAO INFORMADO')
            end, 
          CELULAR = case
                when dbo.TiraLetras(FONE_D1) is not null 
                and dbo.TiraLetras(FONE_D1) <> ' ' 
                and FONE_D1 not like '0%'
                and len(dbo.tiraletras(FONE_D1)) >= 9  
                and substring(dbo.tiraletras(FONE_D1), 3, 1) in('9', '8', '7')
                --then substring(dbo.TiraLetras(celular), 3,9)
                --then dbo.TiraLetras(FONE_D1)
                then IIF(LEN(dbo.TiraLetras(FONE_D1)) = 9, CONCAT('31',dbo.TiraLetras(FONE_D1)), IIF(LEN(dbo.TiraLetras(FONE_D1)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(FONE_D1),1,2),'9',SUBSTRING(dbo.TiraLetras(FONE_D1),3,12)), dbo.TiraLetras(FONE_D1)))
                when dbo.TiraLetras(FONE_D2) is not null 
                and dbo.TiraLetras(FONE_D2) <> ' '
                and FONE_D2 not like '0%'
                and len(dbo.tiraletras(FONE_D2)) >= 9 
                and substring(dbo.tiraletras(FONE_D2), 3, 1) in('9', '8', '7')
                --then substring(dbo.TiraLetras(telefone), 3,9)
                --then dbo.tiraletras(FONE_D2)
                then IIF(LEN(dbo.TiraLetras(FONE_D2)) = 9, CONCAT('31',dbo.TiraLetras(FONE_D2)), IIF(LEN(dbo.TiraLetras(FONE_D2)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(FONE_D2),1,2),'9',SUBSTRING(dbo.TiraLetras(FONE_D2),3,12)), dbo.TiraLetras(FONE_D2)))
                when dbo.TiraLetras(Telefone) is not null 
                and dbo.TiraLetras(Telefone) <> ' '
                and Telefone not like '0%'
                and len(dbo.tiraletras(Telefone)) >= 9 
                and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
                --then dbo.TiraLetras(Telefone)
                then IIF(LEN(dbo.TiraLetras(Telefone)) = 9, CONCAT('31',dbo.TiraLetras(Telefone)), IIF(LEN(dbo.TiraLetras(Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(Telefone),3,12)), dbo.TiraLetras(Telefone)))
                --then substring(dbo.TiraLetras(telefone2), 3,9)
              end, 
              UPPER(funerObito.cidade) CIDADE, funerObito.estado UF,  
              UPPER(f.nome) FUNERARIA, 
        isnull(funerObito.contrato,0) INSCRICAO,
        UPPER(funerObito.NOME) FALECIDO, 
        DECLARANTE = case
              when funerObito.DECLARAN_D <> ' '
              then  UPPER(funerObito.DECLARAN_D)
              when funerObito.DECLARAN_D = ' '
              then  UPPER('NAO INFORMADO')
            end
      FROM funerObito
      INNER JOIN Funerarias f ON funerObito.funeraria = f.funeraria
      WHERE data between '$inicioPeriodo 00:00:00.000' and '$fimPeriodo 23:59:59.998'
      and DECLARAN_D is not null
      and (FONE_D1 not like '0%' 
        and dbo.TiraLetras(FONE_D1) is not null 
        and dbo.TiraLetras(FONE_D1) <> ' ' 
        and substring(dbo.tiraletras(FONE_D1), 3, 1) in('9', '8', '7')
        and LEN(dbo.TiraLetras(FONE_D1)) >= 9 and LEN(dbo.TiraLetras(FONE_D1)) <= 11
        OR 
        FONE_D2 not like '0%'
        and dbo.TiraLetras(FONE_D2) is not null 
        and dbo.TiraLetras(FONE_D2) <> ' '
        and substring(dbo.tiraletras(FONE_D2), 3, 1) in('9', '8', '7')
        and LEN(dbo.TiraLetras(FONE_D2)) >= 9 and LEN(dbo.TiraLetras(FONE_D2)) <= 11
        OR 
        Telefone not like '0%'
        and dbo.TiraLetras(Telefone) is not null 
        and dbo.TiraLetras(Telefone) <> ' '
        and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
        and LEN(dbo.TiraLetras(Telefone)) >= 9 and LEN(dbo.TiraLetras(telefone)) <= 11
      )
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
          $declarante = strtr($r['DECLARANTE'], $tiraacento);
          $falecido = strtr($r['FALECIDO'], $tiraacento); 
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
                $sql = "select count(*) Qtde from NPS_ServicosFunerarios where Inscricao = $inscricao and Telefone = '$telefone' and DataEnvio = '$dataEnvio'";
                $stmt = sqlsrv_query( $conn, $sql );
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                }
        
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                      //echo $row['Qtde']."<br />";
        
                      $qtdeLinhas = $row['Qtde'];
                      if ($qtdeLinhas == 0)
                      {
                        $sql = "INSERT INTO NPS_ServicosFunerarios (Declarante, Falecido, Telefone, Inscricao, Cidade, UF, Funeraria, DataEnvio) VALUES (?,?,?,?,?,?,?,?)";
                        $params = array($declarante, $falecido ,$telefone, $inscricao, $cidade, $uf, $funeraria, $dataEnvio);
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
        $sql = "delete from NPS_ServicosFunerarios where telefone in 
                                                                      (
                                                                      select Telefone
                                                                      from NPS_ServicosFunerarios
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
