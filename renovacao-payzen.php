<?php
// Verificador de sessão 
//require "verifica.php"; 
 
// Conexão com o banco de dados 
//require "comum.php"; 

date_default_timezone_set('America/Fortaleza');
//echo date('Y-m-d', strtotime('+2days'));
//echo date('Y-m-d H:i:s', strtotime('+1days'));

$datainicio = date('Y-m-d', strtotime('+2days'));
//require_once "conn/conexao_mssql_zelodev.php";
$mesanterior =  date('m', strtotime('-1 months', strtotime(date('Y-m-d'))));
$ano =  date('Y', strtotime('-1 months', strtotime(date('Y-m-d'))));

$res_query = "select distinct Mensalidade.Inscricao
,Mensalidade.valor
, cast(replace((Mensalidade.Valor), '.','') as integer) AS Amount
, Mensalidade.Vencimento
, Associados.payzentokencard
, orderRequest.subscriptionId
, IIF((SELECT top 1 inscricao FROM orderRequest WHERE inscricao = Associados.inscricao and gateway = 'ADYEN' AND status = 'A' ORDER BY data_cadastro DESC) IS NULL, 'N', 'S') AS recorrenteAdyen
,'N' recorrenteRenovado
, NULL retornoToken
, NULL retornoSubscription
, NULL newSubscriptiption                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
from Mensalidade 
inner join orderRequest on Mensalidade.Inscricao = orderRequest.inscricao
inner join Associados on Mensalidade.Inscricao = Associados.inscricao
where Vencimento between '$datainicio 00:00:00.000' and '$datainicio 23:59:59.998'
and orderRequest.status = 'A' and orderRequest.gateway is null
and orderRequest.data_cadastro < GETDATE()-1
and Associados.Status in (1,2)
and ((select TOP 1 Valor from Mensalidade where Inscricao = orderRequest.inscricao and  Referencia = '$mesanterior/$ano') <> Mensalidade.valor)
and Mensalidade.Pagamento IS NULL
and Mensalidade.TipoMensalidade = 1
UNION ALL
select distinct M.Inscricao
, M.valor
, cast(replace((M.Valor), '.','') as integer) AS Amount
, M.Vencimento
, a.payzentokencard
, o.subscriptionId
, IIF((SELECT top 1 inscricao FROM orderRequest WHERE inscricao = A.inscricao and gateway = 'ADYEN' AND status = 'A' ORDER BY data_cadastro DESC) IS NULL, 'N', 'S') AS recorrenteAdyen
,'N' recorrenteRenovado
, NULL retornoToken
, NULL retornoSubscription
, NULL newSubscriptiption   
from Mensalidade as M
INNER JOIN orderRequest AS O ON M.Inscricao = O.inscricao
INNER JOIN Associados AS A ON M.Inscricao = A.inscricao
where M.Vencimento between '$datainicio 00:00:00.000' and '$datainicio 23:59:59.998'
AND O.gateway IS NULL AND O.status = 'A' AND O.data_cadastro < GETDATE()-1
AND M.Pagamento IS NULL
AND A.Status IN (1,2)
AND(SELECT Valor FROM Mensalidade WHERE inscricao = A.Inscricao AND Referencia LIKE '$mesanterior/$ano')<>(SELECT ValorPago FROM Mensalidade WHERE inscricao = A.Inscricao AND Referencia LIKE '$mesanterior/$ano')
and M.Pagamento IS NULL
and M.TipoMensalidade = 1
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
  $valor = $r['valor']; 
  $amount = $r['Amount']; 
  $vencimento = $r['Vencimento'];
  $token = $r['payzentokencard'];
  $assinatura = $r['subscriptionId'];
  $recorrenteAdyen = $r['recorrenteAdyen'];
  $recorrenteRenovado = $r['recorrenteRenovado'];

      $serverName = "gold.grupozelo.net";
      $connectionInfo = array( "Database"=>"Zelo", "UID"=>"sa", "PWD"=>"071999gs" );
      $conn = sqlsrv_connect( $serverName, $connectionInfo);
      if( $conn === false ) {
          die( print_r( sqlsrv_errors(), true));
      }

      //valida se ja existe assinatura
        $sql = "select count(*) Qtde from logRenovacaoRecorrente where subscriptionId = '$assinatura'";
        $stmt = sqlsrv_query( $conn, $sql );
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
              //echo $row['Qtde']."<br />";

              $qtdeLinhas = $row['Qtde'];
              if ($qtdeLinhas == 0)
              {
                $sql = "INSERT INTO logRenovacaoRecorrente (Inscricao, valor, Vencimento, payzentokencard, subscriptionId, recorrenteAdyen, recorrenteRenovado, Amount) VALUES (?,?,?,?,?,?,?,?)";
                $params = array($inscricao, $valor , $vencimento, $token, $assinatura, $recorrenteAdyen , $recorrenteRenovado, $amount);

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

        sqlsrv_free_stmt( $stmt);
}
?>
