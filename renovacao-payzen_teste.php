<?php
date_default_timezone_set('America/Fortaleza');

///DADOS DE CONEXÃO
$payzen = 'https://api.payzen.com.br/api-payment';


/// consultando todos os contratos que devem ser renovados.
$res_query = "select Inscricao
                , valor
                , Vencimento
                , payzentokencard
                , subscriptionId
                , recorrenteAdyen
              from logrenovacaorecorrente 
              where retornoToken is null";

              require_once "conn/conexao_mssql_zelo.php";

              try{
               
                  $Conexao    = Conexao::getConnection();
                  $query      = $Conexao->query("$res_query");
                  $resRecorrente   = $query->fetchAll();
               }catch(Exception $e){
               
                  echo $e->getMessage();
                  exit;
              
               }	
               foreach($resRecorrente as $r) {

                $inscricao = $r['Inscricao'];
                $valor = $r['valor']; 
                $vencimento = $r['Vencimento'];
                $token = $r['payzentokencard'];
                $assinatura = $r['subscriptionId'];
                $recorrenteAdyen = $r['recorrenteAdyen'];
                $valor = $r['valor'];

                // Montando JSON de cancelamento recorrente
                $dadosCancelamento = http_build_query(
                  array(
                      'paymentMethodToken' => $token,
                      'subscriptionId' => $assinatura
                  )
                );
                $opcoes = array('http' =>
                     array(
                      'method'  => 'POST',
                      'header' => "Authorization: Basic ODEwMzY4MTY6cHJvZHBhc3N3b3JkX2FiRlFYSHBuRTgxZ3lHczdXNDBQTXRVNzAwbUtCQ2ZNbVVRUTZKZDg2Rm9XMw==\n",
                      "Content-Type: application/json",
                      'content' => $dadosCancelamento
                  )
                );
                $contexto = stream_context_create($opcoes);
                
                $resultado  = @file_get_contents($payzen.'/V4/Subscription/Cancel', false, $contexto);
                
                //sleep(2);
                
                //$json = file_get_contents($json_url);
                $data = json_decode($resultado, TRUE);
                
                //var_dump($data);
                //echo "<pre>";
                //print_r($data);
                //echo "</pre>";
                //echo"<br>";
                $status = $data['status'];
                //echo $status;
                //echo" - " . $inscricao . " - ";
                atualizaLog($assinatura, $status, $recorrenteAdyen, $vencimento, $valor, $token, $payzen, $inscricao);
              }

              function atualizaLog($assinatura, $status, $recorrenteAdyen, $vencimento, $valor, $token, $payzen, $inscricao)
              {
                //echo $assinatura;
                
                $server = 'gold.grupozelo.net';
                $dbName = 'Zelo';
                $uid = 'sa';
                $pwd = '071999gs';

                $options = array("Database"=>$dbName, "UID"=>$uid, "PWD"=>$pwd);
                $conn = sqlsrv_connect($server, $options);
                if($conn === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                // Atualiza log renovação de recorrente conforme cancelado na payzen
                $queryLog = "UPDATE logRenovacaoRecorrente SET retornoToken = '$status' WHERE inscricao = $inscricao and subscriptionId = '$assinatura'";
                $stmt = sqlsrv_query($conn, $queryLog);
                if($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                sqlsrv_free_stmt($stmt);

                if ($status == 'SUCCESS')
                {
                  // Cancela subscription anterior
                  $queryOrder = "UPDATE orderrequest SET status = 'C', dataCancelamento = GETDATE() WHERE inscricao = $inscricao and subscriptionId = '$assinatura'";
                  $stmt = sqlsrv_query($conn, $queryOrder);
                  sqlsrv_free_stmt($stmt);
                  

                  // Ativa recorrente, se o cliente não tiver recorrente na Adyen
                  if ($recorrenteAdyen == 'N')
                  {
                    $hora = date('H');
                    if ($hora <= 18)
                    {
                      $dataEfeito = date('Y-m-d').'T02:00:00+01:00';
                    }else
                    {
                      $dataEfeito = date('Y-m-d', strtotime('+1days')).'T02:00:00+01:00';
                    }
                    
                    //$dataEfeito = date('2021-08-14').'T02:00:00+01:00';
                    //echo " - ".$dataEfeito;
                    $diaVencimento =  number_format(date('d', strtotime(date($vencimento))),0);

                    //Regra dos ultimos dias payzen, else deve ser estatico, para payzen não pular meses
                    if ($diaVencimento < 28)
                    {
                      $rrule = 'RRULE:FREQ=MONTHLY;BYMONTHDAY='.$diaVencimento.';INTERVAL=1';
                    }else
                    {
                      $rrule = 'RRULE:FREQ=MONTHLY;BYMONTHDAY=28,29,30,31;BYSETPOS=-1';
                    }
                    
                    // Aqui converto o valor da mensalidade, se passar o valor como decimal a valor ficará errado
                    
                    $amount = $valor * 100;

                    // Montando JSON de ativação do recorrente
                    
                    $dadosAtivacao = http_build_query(
                      array(
                          'amount' => $amount,
                          'currency' => 'BRL',
                          'effectDate' => $dataEfeito,
                          'paymentMethodToken' => $token,
                          'rrule' => $rrule
                      )
                    );
                    $opcoes = array('http' =>
                        array(
                          'method'  => 'POST',
                          'header' => "Authorization: Basic ODEwMzY4MTY6cHJvZHBhc3N3b3JkX2FiRlFYSHBuRTgxZ3lHczdXNDBQTXRVNzAwbUtCQ2ZNbVVRUTZKZDg2Rm9XMw==\n",
                          "Content-Type: application/json",
                          'content' => $dadosAtivacao
                      )
                    );
                    
                    $contexto = stream_context_create($opcoes);
                  
                    $resultado  = @file_get_contents($payzen.'/V4/Charge/CreateSubscription', false, $contexto);

                    $data = json_decode($resultado, TRUE);
                  
                    //var_dump($data);
                    //echo "<pre>";
                    //print_r($data);
                    //echo "</pre>";
                    //echo"<br>";
                    $statusAssinatura = $data['status'];
                    $novaAssinatura = $data['answer'];
                    $novaAssinatura = $novaAssinatura['subscriptionId'];
                    //echo $novaAssinatura;
                    //echo "<span style='color:green;'> Nova: </span>";
                    echo "====================================================================================================================================";
                    echo "<br>";
                    echo "<b>Contrato: </b>".$inscricao." - <b>Token:</b> ".$token. " - <b>   Assinaturas:</b><span style='color:red;'> Antiga: </span>" .$assinatura."<span style='color:green;'> Nova: </span>".$novaAssinatura;
                    echo "<br>";
                    
                    // Se cadastro de nova assinatura bem sucedido, atualiza order request e log renovação
                    if ($statusAssinatura = 'SUCCESS')
                    {
                      $queryLogRecorrente = "UPDATE logrenovacaorecorrente SET retornoSubscription = '$statusAssinatura', recorrenteRenovado = 'S', newSubscription = '$novaAssinatura' , creatSubscription = GETDATE() WHERE inscricao = $inscricao and subscriptionId = '$assinatura'";
                      $stmt = sqlsrv_query($conn, $queryLogRecorrente);
                      sqlsrv_free_stmt($stmt);

                      $momento = date('Y-m-d H:i:s.B');

                      $sql = "INSERT INTO orderRequest (inscricao, subscriptionId, status, data_cadastro) values (?, ?, 'A', ?)";
                      $params = array($inscricao, $novaAssinatura, $momento);
                      $stmt = sqlsrv_query( $conn, $sql, $params);

                      //echo "<br>". $momento;

                    }
                    // Se o cadastro não ocorreu, grava o erro na tabela log Renovação
                    else
                    {
                      $queryLogRecorrente = "UPDATE logrenovacaorecorrente SET retornoSubscription = '$statusAssinatura' WHERE inscricao = $inscricao and  subscriptionId = '$assinatura'";
                      $stmt = sqlsrv_query($conn, $queryLogRecorrente);
                      sqlsrv_free_stmt($stmt);
                    }
                  }
                }
                sqlsrv_close($conn);

              }
?>
