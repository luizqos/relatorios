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
$planilha = "gerar-nps_servicos_funerarios.php?datai=$datainicio&dataf=$datafim";
$res_query = "SELECT NOME = case when funerObito.DECLARAN_D <> ' ' then funerObito.DECLARAN_D when funerObito.DECLARAN_D = ' ' then 'NÃO INFORMADO' end, CELULAR = case when dbo.TiraLetras(FONE_D1) is not null and dbo.TiraLetras(FONE_D1) <> ' ' and FONE_D1 not like '0%' and len(dbo.tiraletras(FONE_D1)) >= 9 and substring(dbo.tiraletras(FONE_D1), 3, 1) in('9', '8', '7') then dbo.TiraLetras(FONE_D1) when dbo.TiraLetras(FONE_D2) is not null and dbo.TiraLetras(FONE_D2) <> ' ' and FONE_D2 not like '0%' and len(dbo.tiraletras(FONE_D2)) >= 9 and substring(dbo.tiraletras(FONE_D2), 3, 1) in('9', '8', '7') then dbo.tiraletras(FONE_D2) when dbo.TiraLetras(Telefone) is not null and dbo.TiraLetras(Telefone) <> ' ' and Telefone not like '0%' and len(dbo.tiraletras(Telefone)) >= 9 and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7') then dbo.TiraLetras(Telefone) end, funerObito.cidade CIDADE, f.nome FUNERARIA, isnull(funerObito.contrato,0) INSCRICAO, funerObito.NOME FALECIDO, DECLARANTE = case when funerObito.DECLARAN_D <> ' ' then funerObito.DECLARAN_D when funerObito.DECLARAN_D = ' ' then 'NÃO INFORMADO' end FROM funerObito INNER JOIN Funerarias f ON funerObito.funeraria = f.funeraria WHERE data between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000' and DECLARAN_D is not null and (FONE_D1 not like '0%' and dbo.TiraLetras(FONE_D1) is not null and dbo.TiraLetras(FONE_D1) <> ' ' and substring(dbo.tiraletras(FONE_D1), 3, 1) in('9', '8', '7') and LEN(dbo.TiraLetras(FONE_D1)) >= 9 and LEN(dbo.TiraLetras(FONE_D1)) <= 11 OR FONE_D2 not like '0%' and dbo.TiraLetras(FONE_D2) is not null and dbo.TiraLetras(FONE_D2) <> ' ' and substring(dbo.tiraletras(FONE_D2), 3, 1) in('9', '8', '7') and LEN(dbo.TiraLetras(FONE_D2)) >= 9 and LEN(dbo.TiraLetras(FONE_D2)) <= 11 OR Telefone not like '0%' and dbo.TiraLetras(Telefone) is not null and dbo.TiraLetras(Telefone) <> ' ' and substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7') and LEN(dbo.TiraLetras(Telefone)) >= 9 and LEN(dbo.TiraLetras(telefone)) <= 11 ) ORDER BY funerObito.DECLARAN_D";
require_once "conn/".$banco."";

/*
$result_query = "SELECT query FROM `consulta` where id = 1";
$consulta = mysqli_query($conexao , $result_query);

$res_query = "$consulta";
*/


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
            <h3 class="card-title">NPS Serviços Funerários  | <b>Período: </b><?php echo $_POST ["datainicio"]; ?> a <?php echo $_POST ["datafim"]; ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
                <tr>
					<th>NOME</th>
					<th>CELULAR</th>
					<th>CIDADE</th>
					<th>FUNERARIA</th>
					<th>INSCRICAO</th>
					<th>FALECIDO</th>
					<th>DECLARANTE</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo strtoupper($r['NOME']); ?></td>
						<td><?php echo $r['CELULAR']; ?></td>
						<td><?php echo strtoupper($r['CIDADE']); ?></td>
						<td><?php echo strtoupper($r['FUNERARIA']); ?></td>
						<td><?php echo $r['INSCRICAO']; ?></td>
						<td><?php echo strtoupper($r['FALECIDO']); ?></td>
						<td><?php echo strtoupper($r['DECLARANTE']); ?></td>
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