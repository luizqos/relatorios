<?php
header('Content-Type: text/html; charset=UTF-8');
// Verificador de sessão 
//require "verifica.php"; 
// Conexão com o banco de dados 
//require "comum.php"; 

date_default_timezone_set('America/Fortaleza');

$diaSemana = date('D');

//echo "inicio:" .$datainicio;
//echo " - fim:" .$datafim;

       if ( $diaSemana == 'Mon'){
          $dataEnvio = date('Y-m-d');
          $res_query = "select Nome, Telefone, Inscricao, Servico, Cemiterio  
          from NPS_Cemiterios 
          where telefone in 
            (
              select Telefone
              from NPS_Cemiterios
              where  DataEnvio = '$dataEnvio 00:00:00.000'
              group by Telefone
              having count(*) > 1
            )
            and DataEnvio = '$dataEnvio 00:00:00.000' or len(Telefone)>11
                order by Telefone, Servico desc";
          require_once "conn/conexao_mssql_zelo.php";
          
          
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
        echo"<th>Servico</th>";
        echo"<th>Cemiterio</th>";
        echo"</tr>";
       
         foreach($resultado as $limpaTelefone) {
          

          $nome = $limpaTelefone['Nome'];
          $telefone = $limpaTelefone['Telefone'];
          $inscricao = $limpaTelefone['Inscricao'];
          $servico = $limpaTelefone['Servico'];
          $cemiterio = $limpaTelefone['Cemiterio'];

            if (strlen($telefone) == 11 )
            {
              
              echo "<tr>";
              echo "<td>$nome</td>";
              echo "<td>$inscricao</td>";
              echo "<td>$telefone</td>";
              echo "<td>$servico</td>";
              echo "<td>$cemiterio</td>";
              echo "</tr>";

              $serverName = "gold.grupozelo.net";
              $connectionInfo = array( "Database"=>"Zelo", "UID"=>"sa", "PWD"=>"071999gs" );
              $conn = sqlsrv_connect( $serverName, $connectionInfo);
              if( $conn === false ) {
                  die( print_r( sqlsrv_errors(), true));
              }

              //valida se ja existe o regisrto
              $sql = "select count(*) Qtde from NPS_Cemiterios where Telefone = '$telefone' and lote is null";
              $stmt = sqlsrv_query( $conn, $sql );
              if( $stmt === false) {
                  die( print_r( sqlsrv_errors(), true) );
              }

              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
              {
                $duplicados = $row['Qtde'];
                //echo "DUPLICADOS: ".$duplicados."</br>";

                if ($duplicados > 1)
                  {
                    $sql = "delete from NPS_Cemiterios where telefone = '$telefone' and Servico = '$servico' and lote is null";
                    $stmt = sqlsrv_query( $conn, $sql );
                    if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                  }
                }
              }
            }  
            sqlsrv_free_stmt( $stmt);
  }
  echo"</table>";
}
?>
