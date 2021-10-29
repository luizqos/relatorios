<?php
date_default_timezone_set('America/Fortaleza');

$data = date('ymd', strtotime('-1days'));

$arquivo = 'L:\JT_GRUPO_ZELO_81036816_' . $data . '.csv';

//echo $arquivo;
                $server = 'gold.grupozelo.net';
                $dbName = 'Zelo';
                $uid = 'sa';
                $pwd = '071999gs';

				  $serverName = "$server";
				  $connectionInfo = array( "Database"=>"$dbName", "UID"=>"$uid", "PWD"=>"$pwd" );
				  $conn = sqlsrv_connect( $serverName, $connectionInfo);
				  if( $conn === false ) {
					  die( print_r( sqlsrv_errors(), true));
					}

				//valida se ja existe arquivo
                $sql = "select count(*) Qtde from tblretorno where arquivo  = '$arquivo'";
                $stmt = sqlsrv_query( $conn, $sql );
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                }

              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
              //echo $row['Qtde']."<br />";

              $qtdeLinhas = $row['Qtde'];
              if ($qtdeLinhas == 0)
              {
                $sql = "INSERT INTO tblretorno (arquivo, boletoconta, baixado, reenviar, CodigoUsuario, datahoracadastro) VALUES (?, 9, 'N', 'N', 1, GETDATE())";
                $params = array($arquivo);

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
        sqlsrv_close($conn);
				
				

?>
