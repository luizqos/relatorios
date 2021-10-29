<?php
date_default_timezone_set('America/Fortaleza');
$diaSemana = date('D');

if ($diaSemana == 'Mon'){

$envio = date('Y-m-d') . ' 11:00:00';
$date = date_create($envio);
$schedule = date_format($date, 'U');


///DADOS DE CONEXÃO
$tracksale = 'https://api.tracksale.co/v2/campaign/';

$cemiterio=1;
while ($cemiterio<=5){
    $lote = null;
/// consultando todos os contratos que devem ser enviados nps.
$res_query = "SELECT 1 Id, 'DUCASH' Nome, '31984128731' Telefone, 1234144 Inscricao, 'BELO HORIZONTE' Cidade, $cemiterio Cemiterio
UNION ALL
SELECT 2 Id, 'DUCASH' Nome, '31992972242' Telefone, 1234144 Inscricao, 'BELO HORIZONTE' Cidade, $cemiterio Cemiterio
UNION ALL
SELECT 3 Id, 'DUCASH' Nome, '31999992848' Telefone, 1234144 Inscricao, 'BELO HORIZONTE' Cidade, $cemiterio Cemiterio
UNION ALL
select Id
                    , Nome
                    , Telefone
                    , Inscricao
                    , Cidade
                    , Cemiterio
from NPS_Cemiterios
where Cemiterio = $cemiterio and (lote is null 
or len(lote)=0)
order by Cemiterio ASC";

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
                $id = $r['Id'];
                $nome = $r['Nome'];
                $inscricao = $r['Inscricao'];
                $telefone = $r['Telefone']; 
                $cidade = $r['Cidade'];
                $cemiterio = $r['Cemiterio'];

                switch ($cemiterio) {
                    case 1:
                        $campanha = 6;
                        $nomeCemiterio = 'COLINA BH';
                        break;
                    case 2:
                        $campanha = 11;
                        $nomeCemiterio = 'COLINA NT';
                        break;
                    case 3:
                        $campanha = 7;
                        $nomeCemiterio = 'BELO VALE';
                        break;
                    case 4:
                        $campanha = 23;
                        $nomeCemiterio = 'JD. PAINEIRAS';
                        break;
                    case 5:
                        $campanha = 24;
                        $nomeCemiterio = 'VERTICAL GUARULHOS';
                        break;
                }
                
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
                                \"name\" : \"Contrato\",
                                \"value\" : \"$inscricao\"
                            },
                           {
                                \"name\" : \"Nome do Cliente\",
                                \"value\" : \"$nome\"
                            },
                            {
                                \"name\" : \"Cidade\",
                                \"value\" : \"$cidade\"
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
                                      \"name\" : \"Contrato\",
                                      \"value\" : \"$inscricao\"
                                  },
                                 {
                                      \"name\" : \"Nome do Cliente\",
                                      \"value\" : \"$nome\"
                                  },
                                  {
                                      \"name\" : \"Cidade\",
                                      \"value\" : \"$cidade\"
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

                if($nome <> 'DUCASH')
                {
                    $query = "UPDATE NPS_Cemiterios SET lote = '$lote' WHERE id = $id";
                    $stmt = sqlsrv_query($conn, $query);
                    if($stmt === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                    sqlsrv_free_stmt($stmt);
                    sqlsrv_close($conn);
                }
    }
        
   echo "Lote :" . $lote. " Cemiterio: ".$nomeCemiterio."</br>";
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
                $cemiterio = $cemiterio + 1;
}
}

?>
