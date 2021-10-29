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

$planilha = "#";

$res_query = "SELECT pfunc.NOME
, LEFT(PPESSOA.CPF,3)+'.'+SUBSTRING(PPESSOA.CPF,4,3)+'.'+SUBSTRING(PPESSOA.CPF,7,3)+'-'+SUBSTRING(PPESSOA.CPF,10,2) CPF
, pfunc.CHAPA
, pfunc.DATAADMISSAO
, pcodsituacao.DESCRICAO CODSITUACAO
, pfunc.CODCOLIGADA
, pfunc.CODFILIAL
, pfunc.CODFUNCAO
FROM pfunc 
INNER JOIN PPESSOA
ON PPESSOA.CODIGO = pfunc.CODPESSOA
INNER JOIN pcodsituacao
ON pcodsituacao.CODCLIENTE = pfunc.CODSITUACAO
--WHERE pfunc.codsituacao in ('A', 'E', 'F')
--and pfunc.nome like 'JENNIFER cr%'
ORDER BY pfunc.codcoligada asc
";
require_once "conn/conexao_mssql_rm.php";


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
            <h3 class="card-title">Funcionarios</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
				<tr>
					<th>NOME</th>
					<th>CPF</th>
					<th>CHAPA</th>
					<th>DATA DE ADMISSÃO</th>
					<th>SITUAÇÃO</th>
					<th>COLIGADA</th>
                    <th>FILIAL</th>
                    <th>FUNÇÃO</th>
				</tr>
                </thead>
                <tbody> 
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['NOME']; ?></td>
						<td><?php echo strtoupper($r['CPF']); ?></td>
						<td><?php echo strtoupper($r['CHAPA']); ?></td>
						<td><?php echo $r['DATAADMISSAO']; ?></td>
						<td><?php echo $r['CODSITUACAO']; ?></td>
            <td><?php echo $r['CODCOLIGADA']; ?></td>
            <td><?php echo $r['CODFILIAL']; ?></td>
            <td><?php echo $r['CODFUNCAO']; ?></td>
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
