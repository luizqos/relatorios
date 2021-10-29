<?php
date_default_timezone_set('America/Fortaleza');

$envio = date('Y-m-d') . ' 11:00:00';
$date = date_create($envio);
$schedule = date_format($date, 'U');


///DADOS DE CONEXÃO
$tracksale = 'https://api.tracksale.co/v2/campaign/';
$campanha = 26;
$lote = null;


/// consultando todos os contratos que devem ser enviados nps.
$res_query = "select upper(N.Nome)as Nome
                        , N.Telefone
                        , N.Inscricao
                        , N.Cidade
                        , N.UF
                        , F.Empresa AS Filial
                        , N.Id
    from NPS_AtendimentoPresencial as N
    inner join Filiais as F on F.Codigo = N.Filial
    inner join Usuarios as u on u.Codigo = n.Usuario
    where N.lote is null  or N.loTE = ' '
    order by N.Id asc";

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
                $nome = $r['Nome'];
                $inscricao = $r['Inscricao'];
                $telefone = $r['Telefone']; 
                $cidade = $r['Cidade'];
                $uf = $r['UF'];
                $funeraria = $r['Filial'];
                $id = $r['Id'];
                
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $tracksale.$campanha.'/dispatch');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "{ 
                  \"customers\": [
                    {
                        \"name\":\"$nome\",
                        \"phone\": \"$telefone\",
                        \"tags\" : [
                            {
                                \"name\" : \"Inscrição\",
                                \"value\" : \"$inscricao\"
                            },
                            {
                                \"name\" : \"Cidade\",
                                \"value\" : \"$cidade\"
                            },
                           {
                                \"name\" : \"UF\",
                                \"value\" : \"$uf\"
                            },
                           {
                                \"name\" : \"Funerária\",
                                \"value\" : \"$funeraria\"
                            }
                        ]
                    }
                ]

                }"
                
               
                );
                
                $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Authorization: bearer 4ab9fae186fb164f6f87ea6076a68dd8';
                $headers[] = 'cache-control: no-cache';
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                $result = curl_exec($ch);

                $data = json_decode($result, TRUE);
                $lote = $data['dispatch_code'];
                
                $i=0;
  
                if (curl_errno($ch)) {
                    $erro = 1;
                    while ($erro == 1 && $i<=5) {
                      $i=$i+1;


                      /*
                      echo "Loop -";
                      echo $id;
                      echo " - ";
                      echo $inscricao;
                      echo " - ";
                      echo $i;
                      echo"<br>";
                      */
                      $ch = curl_init();

                      curl_setopt($ch, CURLOPT_URL, $tracksale.$campanha.'/dispatch');
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                      curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, "{ 
                        \"customers\": [
                          {
                              \"name\":\"$nome\",
                              \"phone\": \"$telefone\",
                              \"tags\" : [
                                  {
                                      \"name\" : \"Inscrição\",
                                      \"value\" : \"$inscricao\"
                                  },
                                  {
                                      \"name\" : \"Cidade\",
                                      \"value\" : \"$cidade\"
                                  },
                                 {
                                      \"name\" : \"UF\",
                                      \"value\" : \"$uf\"
                                  },
                                 {
                                      \"name\" : \"Funerária\",
                                      \"value\" : \"$funeraria\"
                                  }
                              ]
                          }
                      ]

                      }"
                      
                     
                      );
                      
                      $headers = array();
                      $headers[] = 'Accept: application/json';
                      $headers[] = 'Authorization: bearer 4ab9fae186fb164f6f87ea6076a68dd8';
                      $headers[] = 'cache-control: no-cache';
                      $headers[] = 'Content-Type: application/json';
                      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                      
                      $result = curl_exec($ch);

                      $data = json_decode($result, TRUE);
                      $lote = $data['dispatch_code'];

                      if (curl_errno($ch)) {
                        $erro = 1;
                      }
                      else {
                        $erro = 0;
                      }
      
                  
                    }

                }
                curl_close($ch);

                $server = 'gold.grupozelo.net';
                $dbName = 'Zelo';
                $uid = 'sa';
                $pwd = '071999gs';

                $options = array("Database"=>$dbName, "UID"=>$uid, "PWD"=>$pwd);
                $conn = sqlsrv_connect($server, $options);
                if($conn === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                
                $query = "UPDATE NPS_AtendimentoPresencial SET lote = '$lote' WHERE id = $id";
                $stmt = sqlsrv_query($conn, $query);
                if($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
    }
        
   //echo "lote :" . $lote;
   sleep(10);
    if ($lote != null)
    {
      $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $tracksale.$campanha.'/dispatch'.'/'.$lote);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "{ 

                    \"time\": $schedule


                }"
                
               
                );
                
                $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Authorization: bearer 4ab9fae186fb164f6f87ea6076a68dd8';
                $headers[] = 'cache-control: no-cache';
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                $result = curl_exec($ch);

                $data = json_decode($result, TRUE);
                //var_dump($data);
    }


?>
