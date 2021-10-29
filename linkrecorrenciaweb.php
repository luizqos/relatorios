<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

function converteData($data){
    if(count(explode("/",$data)) > 1): 
         return implode("-",array_reverse(explode("/",$data)));
    elseif(count(explode("-",$data)) > 1): 
         return implode("/",array_reverse(explode("-",$data)));
    endif;
}
$planilha = '#';
$inscricao  = $_POST ["inscricao"];
$banco  = $_POST ["banco"];
$res_query = "select '1 - ZTEC' BANCO
, A.inscricao INSCRICAO
, s.descricao as STATUS
, IIF((A.ASSTIPOCOBRANCA = '7'), IIF( (select TOP 1 IIF(gateway IS NULL, 'PAYZEN', 'ADYEN') from orderRequest where inscricao = A.INSCRICAO AND STATUS = 'A' ORDER BY data_cadastro DESC) IS NOT NULL, CONCAT('RECORRENTE', ' - ', (select TOP 1 IIF(gateway IS NULL, 'PAYZEN', 'ADYEN') from orderRequest where inscricao = A.INSCRICAO AND STATUS = 'A' ORDER BY data_cadastro DESC)), 'RECORRENTE'), TipoCobranca.TipCobDescricao) TIPOCOBRANCA
, A.payzentokencard PTK
, A.nome NOME
, A.CPF CPF
, A.AssEmail EMAIL
--, CONCAT('https://contratoeletronico.grupozelo.com/', A.LinkVenda) AS LINK
, A.TipoAceite TIPOACEITE, A.aceite ACEITE, CONVERT(VARCHAR(10), DataAceite, 103) AS DATAACEITE
, CONVERT(VARCHAR(10), DataEnvioContrato, 103) DATACONTRATO, TA.Descricao DESCRICAO
from Associados as A
left join TipoAceite AS TA
ON TA.Codigo = A.TipoAceite
left join TipoCobranca
on TipoCobranca.TipCobCodigo = a.AssTipoCobranca
inner join status as s
on s.codigo = a.status
where Inscricao = '$inscricao'";
require_once "conn/".$banco."";


try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("$res_query");
	$resultado   = $query->fetchAll();

 }catch(Exception $e){
 
    echo $e->getMessage();
    exit;

 }	
	
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gerenciador de Relatórios | Grupo Zelo</title>
  <link rel="icon" type="image/png" href="lte/dist/img/favicon.png"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <!-- Font Awesome -->
  <link rel="stylesheet" href="lte/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="lte/dist/css/adminlte.min.css">
  <!-- Css Personalizado -->
  <link rel="stylesheet" href="css/person.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-collapse">
<div class="wrapper">
<?php
include('navbar.php');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
            <h3 class="card-title">Aceite Eletrônico</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>BANCO</th>
					<th>INSCRICAO</th>
					<th>STATUS</th>
					<th>COBRANCA</th>
					<th>PAYZEN</th>
					<th>NOME</th>
					<th>CPF</th>
					<th>EMAIL</th>
					<th>TIPO ACEITE</th>
					<th>ACEITE</th>
					<th>DATA ACEITE</th>
					<th>ENVIO</th>
					<th>DESCRICAO</th>
					<th>LOG</th>
                </tr>
                </thead>
                <tbody>
				<?php
					foreach($resultado as $r) {
						//<i class="fas fa-search-dollar"></i>
				?>
					
					<tr>
						<td><?php echo $r['BANCO']; ?></td>
						<td><?php echo $r['INSCRICAO']; ?></td>
						<td><?php echo strtoupper($r['STATUS']); ?></td>
						<td><?php echo $r['TIPOCOBRANCA']; ?></td>
						<td><?php echo $r['PTK']; ?></td>
						<td><?php echo strtoupper($r['NOME']); ?></td>
						<td><?php echo $r['CPF']; ?></td>
						<td><?php echo strtolower($r['EMAIL']); ?></td>
						<td><?php echo $r['TIPOACEITE']; ?></td>
						<td><?php echo $r['ACEITE']; ?></td>
						<td><?php echo $r['DATAACEITE']; ?></td>
						<td><?php echo $r['DATACONTRATO']; ?></td>
						<td><?php echo strtoupper($r['DESCRICAO']); ?></td>
						<td>
						<a class="btn btn-primary" href="javascript:abrir_log_pagamento('/relatorios/pagamentos.php?inscricao=<?php echo strtoupper($r['INSCRICAO']);?>');"><i class="fas fa-search-dollar"></i></a>
						<a class="btn btn-danger" href="javascript:abrir_log_aceite('/relatorios/envios.php?inscricao=<?php echo strtoupper($r['INSCRICAO']);?>');"><i class="fas fa-eye"></i></a>
						</td>
					</tr>
				<?php } ?>
				<tr>
				<?php
					// Dados do banco
					$server = 'gold.grupozelo.net';
					$port = '1443'; // porta padrão
					$server = $port !== '1443' && is_string($port) ? $server .= ", $port": $server;
					$database = 'Z2';
					$user = 'sa';
					$pass = '071999gs';


					$conninfo = array("Database" => $database, "UID" => $user, "PWD" => $pass);
					$conn = sqlsrv_connect($server, $conninfo);
					
					$instrucaoSQL = "select  '2 - PORTAL' BANCO
					, A.inscricao INSCRICAO
					, 'N/A' STATUS
					, IIF (A.ASSTIPOCOBRANCA = 7, 'COBRANÇA RECORRENTE', IIF(A.ASSTIPOCOBRANCA = 1, 'BOLETO', CONVERT(VARCHAR(10),A.ASSTIPOCOBRANCA))) AS TIPOCOBRANCA
					, A.payzentokencard PTK
					, A.nome NOME
					, A.CPF CPF
					, A.Email EMAIL
					--, CONCAT('https://contratoeletronico.grupozelo.com/', A.LinkVenda) AS LINK
					, A.TipoAceite TIPOACEITE
					, A.aceite ACEITE, CONVERT(VARCHAR(10), DataAceite, 103) AS DATAACEITE
					, CONVERT(VARCHAR(10), DataEnvioContrato, 103) DATACONTRATO
					, IIF (A.TipoAceite = 1, 'ACEITE', IIF(A.TipoAceite = 2, 'ACEITE + ADESÃO', IIF(A.TipoAceite = 3, 'ACEITE + ADESÃO + COBRANÇA CONTINUADA', ' '))) AS DESCRICAO
					from Associados as A
					where Inscricao = '$inscricao'";
					
					$params = array();
					$options =array("Scrollable" => SQLSRV_CURSOR_KEYSET);
					$result = sqlsrv_query($conn, $instrucaoSQL, $params, $options);

					for ($i = 0; $i < sqlsrv_num_rows($result); ++$i)
						{
							$line = sqlsrv_fetch_array($result);
							Echo "<td>$line[0]</td>";
							Echo "<td>$line[1]</td>";
							Echo "<td>$line[2]</td>";
							Echo "<td>$line[3]</td>";
							Echo "<td>$line[4]</td>";
							Echo "<td>$line[5]</td>";
							Echo "<td>$line[6]</td>";
							Echo "<td>$line[7]</td>";
							Echo "<td>$line[8]</td>";
							Echo "<td>$line[9]</td>";
							Echo "<td>$line[10]</td>";
							Echo "<td>$line[11]</td>";
							Echo "<td>$line[12]</td>";
							Echo "<td></td>";
						}
				?>
			</tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php
include('footer.php');
?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="lte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="lte/plugins/datatables/jquery.dataTables.js"></script>
<script src="lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="lte/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="lte/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": true,
    });
  });
</script>
<script>
			function abrir_log_aceite(URL) 
			{
				window.open(URL, 'janela', 'width=1080, height=720, top=100, left=699, scrollbars=yes, status=no, toolbar=no, location=no, menubar=no, resizable=no, fullscreen=no')
			}
 		</script>
<script>
function abrir_log_pagamento(URL) 
{
	window.open(URL, 'janela', 'width=1080, height=720, top=100, left=699, scrollbars=yes, status=no, toolbar=no, location=no, menubar=no, resizable=no, fullscreen=no')
}
</script>
		 
</body>
</html>

