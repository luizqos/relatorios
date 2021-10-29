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
$banco  = $_POST ["banco"];
$datainicio  = converteData($_POST ["datainicio"]);
$datafim  = converteData($_POST ["datafim"]);	
$planilha = "gerar-aceite.php?datai=$datainicio&dataf=$datafim";
$res_query = "SELECT 
  A.Inscricao
, A.NOME														AS 'Nome'
, V.NOME														AS 'Vendedor'
, C.NOME														AS 'Cidade'
, CONVERT(VARCHAR(10), A.DataEnvioContrato, 103)				AS 'DtEnvioContrato'
, A.TIPOACEITE													AS 'Tipo Aceite'
, A.Aceite													    AS 'Aceite'
, S.DESCRICAO													AS 'Status'
, CONVERT(VARCHAR(10), A.DataAceite, 103)						AS 'DtAceite'
FROM ASSOCIADOS A
INNER JOIN VENDEDORES V		ON V.CODIGO = A.VENDEDOR
INNER JOIN Status S			ON S.Codigo = A.Status
INNER JOIN CIDADES C		ON C.CODIGO = A.CIDADE
INNER JOIN INSCRICAO I		ON I.INSCRICAO = A.INSCRICAO
WHERE 
A.DATA between '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999'
AND A.LINKVENDA IS NOT NULL
AND A.DataEnvioContrato IS NOT NULL
--AND I.PARCELA = '1'
ORDER BY A.INSCRICAO";
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
            <h3 class="card-title">Relatório de Aceite</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
                <tr>
					<th>INSCRIÇÃO</th>
					<th>CLIENTE</th>
					<th>VENDEDOR</th>
					<th>CIDADE</th>
					<th>ENVIO DO CONTRATO</th>
					<th>TIPO DE ACEITE</th>
					<th>ACEITE</th>
					<th>DATA DO ACEITE</th>
					<th>STATUS</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['Inscricao']; ?></td>	
						<td><?php echo strtoupper($r['Nome']); ?></td>
						<td><?php echo strtoupper($r['Vendedor']); ?></td>
						<td><?php echo strtoupper($r['Cidade']); ?></td>
						<td><?php echo $r['DtEnvioContrato']; ?></td>
						<td><?php echo $r['Tipo Aceite']; ?></td>
						<td><?php echo $r['Aceite']; ?></td>										
						<td><?php echo $r['DtAceite']; ?></td>
						<td><?php echo $r['Status']; ?></td>
					</tr>
				<?php } ?>
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
</body>
</html>

