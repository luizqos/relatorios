<?php
header('Content-Type: text/html; charset=UTF-8');
// Verificador de sessão 
//require "verifica.php"; 
 
// Conexão com o banco de dados 
//require "comum.php"; 
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
date_default_timezone_set('America/Fortaleza');
$diaAnterior = date('Y-m-d', strtotime('-1days'));
//echo "Dia:" .$diaAnterior;
$diaSemana = date('D');
//echo "Dia:" .$diaSemana;

//echo "</br>";
        if ($diaSemana == "Sat" ){
          $dataEnvio = date('Y-m-d', strtotime('+2days'));
          echo "Enviio:" .$dataEnvio."</br>";
        }
        else 
        {
        if ($diaSemana == "Sun"){
          $dataEnvio = date('Y-m-d', strtotime('+1days'));
          echo "Envio:" .$dataEnvio."</br>";
        }else{
          $dataEnvio = date('Y-m-d');
          echo "Envio:" .$dataEnvio."</br>";
        }
        }


        //echo "Envio fim if:" .$dataEnvio."</br>";


        $res_query = "select A.Nome,
        Telefone = case
                when dbo.TiraLetras(A.Celular) is not null 
                and dbo.TiraLetras(A.Celular) <> ' ' 
                and Celular not like '0%'
                and len(dbo.tiraletras(A.Celular)) >= 9  
                and (substring(dbo.tiraletras(A.Celular), 3, 1) in('9', '8', '7') 
                    OR substring(dbo.tiraletras(A.Celular), 1, 1) in('9', '8', '7')
                  )
                --then substring(dbo.TiraLetras(A.Celular), 3,9)
                then IIF(LEN(dbo.TiraLetras(A.Celular)) = 9, CONCAT('31',dbo.TiraLetras(A.Celular)), IIF(LEN(dbo.TiraLetras(A.Celular)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(A.Celular),1,2),'9',SUBSTRING(dbo.TiraLetras(A.Celular),3,12)), dbo.TiraLetras(A.Celular)))
                when dbo.TiraLetras(A.Telefone2) is not null 
                and dbo.TiraLetras(A.Telefone2) <> ' '
                and Telefone2 not like '0%'
                and len(dbo.tiraletras(A.Telefone2)) >= 9 
                and (substring(dbo.tiraletras(A.Telefone2), 3, 1) in('9', '8', '7')
                    OR substring(dbo.tiraletras(A.Telefone2), 1, 1) in('9', '8', '7')
                  )
                --then substring(dbo.TiraLetras(A.Telefone), 3,9)
                --then dbo.tiraletras(A.Telefone2)
                then IIF(LEN(dbo.TiraLetras(A.Telefone2)) = 9, CONCAT('31',dbo.TiraLetras(A.Telefone2)), IIF(LEN(dbo.TiraLetras(A.Telefone2)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(A.Telefone2),1,2),'9',SUBSTRING(dbo.TiraLetras(A.Telefone2),3,12)), dbo.TiraLetras(A.Telefone2)))
                when dbo.TiraLetras(A.Telefone) is not null 
                and dbo.TiraLetras(A.Telefone) <> ' '
                and A.Telefone not like '0%'
                and len(dbo.tiraletras(A.Telefone)) >= 9 
                and (substring(dbo.tiraletras(A.Telefone), 3, 1) in('9', '8', '7')
                    OR substring(dbo.tiraletras(A.Telefone), 1, 1) in('9', '8', '7')
                  )
                --then dbo.TiraLetras(A.Telefone)
                then IIF(LEN(dbo.TiraLetras(A.Telefone)) = 9, CONCAT('31',dbo.TiraLetras(A.Telefone)), IIF(LEN(dbo.TiraLetras(A.Telefone)) = 10, CONCAT(SUBSTRING(dbo.TiraLetras(A.Telefone),1,2),'9',SUBSTRING(dbo.TiraLetras(A.Telefone),3,12)), dbo.TiraLetras(A.Telefone)))
                --then substring(dbo.TiraLetras(A.Telefone2), 3,9)
              end,
            A.Inscricao
        --, A.UsuUpdate, U.Nome, U.setor,u.tipousu, s.Descricao
          ,UPPER(CidadesIBGE.nome) Cidade 
          ,UfIBGE.SIGLA UF
          ,A.Grupo
          ,CONVERT(VARCHAR(10),A.DataUpdate, 127) AS DataAtendimento
          , U.Filial AS Filial
          , U.Codigo AS Usuario
        from associados as A
        inner join Usuarios as U on U.Codigo = A.UsuUpdate
        inner join setorusuarios as S on U.setor = S.codigo
        inner join cidades ON A.cidade = cidades.codigo
        left outer join CidadesIBGE ON Cidades.CodigoIBGE = CidadesIBGE.ID
        left outer join UfIBGE ON CidadesIBGE.IDUF = UfIBGE.CODIGO
        left outer join Filiais AS F ON U.Filial = F.Codigo
        where A.DataUpdate between '$diaAnterior 00:00:00.000' and '$diaAnterior 23:59:59.998'
        and a.Grupo not in ('PRE', 'ZELO')
        and( U.setor not in (9,14,12,7,10) and (U.tipousu is null or u.tipousu = 5) )
        and (U.Departamento not in (44) or u.Departamento is null)
        --and ((u.tipousu = 5) or (U.setor in (2,1) and U.tipousu IS NULL) )
        --or( U.setor not in (9,14,12,7,10) and U.tipousu is null )
        and (A.Celular not like '0%' 
        and dbo.TiraLetras(A.Celular) is not null 
        and dbo.TiraLetras(A.Celular) <> ' ' 
        and substring(dbo.tiraletras(A.Celular), 3, 1) in('9', '8', '7')
        and LEN(dbo.TiraLetras(A.Celular)) >= 9 and LEN(dbo.TiraLetras(A.Celular)) <= 11
        OR 
        Telefone2 not like '0%'
        and dbo.TiraLetras(A.Telefone2) is not null 
        and dbo.TiraLetras(A.Telefone2) <> ' '
        and substring(dbo.tiraletras(A.Telefone2), 3, 1) in('9', '8', '7')
        and LEN(dbo.TiraLetras(A.Telefone2)) >= 9 and LEN(dbo.TiraLetras(A.Telefone2)) <= 11
        OR 
        A.Telefone not like '0%'
        and dbo.TiraLetras(A.Telefone) is not null 
        and dbo.TiraLetras(A.Telefone) <> ' '
        and substring(dbo.tiraletras(A.Telefone), 3, 1) in('9', '8', '7')
        and LEN(dbo.TiraLetras(A.Telefone)) >= 9 and LEN(dbo.TiraLetras(A.Telefone)) <= 11
        ) and LEN(A.NOME) > 4
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
        
          $inscricao = $r['Inscricao'];
          $nome = strtr($r['Nome'], $tiraacento);
          $telefone = $r['Telefone']; 
          $grupo = $r['Grupo']; 
          $cidade = strtr($r['Cidade'], $tiraacento);
          $uf = $r['UF'];
          $dataAtendimento = $r['DataAtendimento'];
          $filial = strtr($r['Filial'], $tiraacento);
          $usuario = strtr($r['Usuario'], $tiraacento);
        
              $serverName = "gold.grupozelo.net";
              $connectionInfo = array( "Database"=>"Zelo", "UID"=>"sa", "PWD"=>"071999gs" );
              $conn = sqlsrv_connect( $serverName, $connectionInfo);
              if( $conn === false ) {
                  die( print_r( sqlsrv_errors(), true));
              }
        
              //valida se ja existe o regisrto
                $sql = "select count(*) Qtde from NPS_AtendimentoPresencial where Inscricao = $inscricao and Telefone = '$telefone' and DataAtendimento = '$dataAtendimento'";
                $stmt = sqlsrv_query( $conn, $sql );
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                }
        
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                      //echo $row['Qtde']."<br />";
        
                      $qtdeLinhas = $row['Qtde'];
                      if ($qtdeLinhas == 0)
                      {
                        $sql = "INSERT INTO NPS_AtendimentoPresencial (Nome, Telefone, Inscricao, Cidade, UF, Grupo, DataAtendimento, DataEnvio, Filial, Usuario) VALUES (?,?,?,?,?,?,?,?,?,?)";
                        $params = array($nome, $telefone , $inscricao, $cidade, $uf, $grupo, $dataAtendimento, $dataEnvio, $filial, $usuario);
                        //echo " - Nome ". $nome . " - Inscricao ". $inscricao . " - Cidade ". $cidade . " - UF ". $uf . " - telefone ". $telefone . " - grupo ". $grupo . " - Atendimento ". $dataAtendimento . " - Envio ". $dataEnvio . " - filial ". $filial . " - usuario ". $usuario;
                        
                        
                        echo "</br>";
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
        $sql = "delete from NPS_AtendimentoPresencial where telefone in 
                                                                      (
                                                                      select Telefone
                                                                      from NPs_atendimentopresencial
                                                                      where  dataatendimento = '$dataAtendimento 00:00:00.000'
                                                                      group by Telefone
                                                                      having count(*) > 1
                                                                      )
                 and dataatendimento = '$dataAtendimento 00:00:00.000' or len(Telefone)>11";
        $stmt = sqlsrv_query( $conn, $sql );
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }
        sqlsrv_free_stmt( $stmt);
?>
