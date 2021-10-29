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

$datalimite  = converteData('26/09/2021');
$datainicio  = converteData($_POST ["datainicio"]);
/*
if ($datalimite > $datainicio ){
  $datainicio = $datalimite;
}
else {
  $datainicio  = converteData($_POST ["datainicio"]);
}
*/
$datafim  = converteData($_POST ["datafim"]);	
$planilha = '#';
$banco  = $_POST ["banco"];
$res_query = "select Inscricao
, LA.Nome as Associado
, LA.Grupo
, LA.SubGrupo
, CONCAT(V.Codigo, ' - ', V.Nome) AS Vendedor
, CONCAT(S.Codigo, ' - ', S.Nome) AS Supervisor
, CONCAT(C.Codigo, ' - ', C.Nome) AS Coordenador
, UPPER(CidadesIBGE.NOME) AS Cidade
, UfIBGE.SIGLA AS UF
, concat(CONVERT(varchar(10), DataHoraInsercao, 103), ' - ', CONVERT(varchar(10), DataHoraInsercao, 108)) DataEvento
, (select Descricao from Status where Codigo = (select top 1 Status from LogAssociado where Inscricao = LA.Inscricao and DataHoraInsercao = LA.DataHoraInsercao order by Codigo desc)) Status
, UPPER(M.Motivo) AS Motivo
, concat(((select top 1 Status from LogAssociado where Inscricao = LA.Inscricao and DataHoraInsercao = LA.DataHoraInsercao order by Codigo asc)),((select top 1 Status from LogAssociado where Inscricao = LA.Inscricao and DataHoraInsercao = LA.DataHoraInsercao order by Codigo desc))) cstatus
, (select Nome from Usuarios where Codigo = (select top 1 UsuUpdate from LogAssociado where Inscricao = LA.Inscricao and DataHoraInsercao = LA.DataHoraInsercao order by Codigo desc)) Usuario
from logassociado as LA
INNER JOIN Vendedores AS V
ON V.Codigo = LA.Vendedor
INNER JOIN Supervisores AS S
ON S.Codigo = LA.Supervisor
INNER JOIN Coordenadores AS C
ON C.Codigo = LA.Coordenador
INNER JOIN Motivo AS M
ON LA.MotivoStat = M.Codigo
LEFT OUTER JOIN Cidades
ON LA.Cidade = Cidades.Codigo
LEFT OUTER JOIN CidadesIBGE
ON CidadeS.CodigoIBGE = CidadesIBGE.ID
LEFT OUTER JOIN UfIBGE
ON CidadesIBGE.IDUf = UfIBGE.CODIGO
where DataHoraInsercao between '$datainicio 00:00:00' and '$datafim 23:59:59'
and (select top 1 Status from LogAssociado where Inscricao = LA.Inscricao and DataHoraInsercao = LA.DataHoraInsercao order by Codigo asc) <> (select top 1 Status from LogAssociado where Inscricao = LA.Inscricao and DataHoraInsercao = LA.DataHoraInsercao order by Codigo desc)
and LA.Status = 7
order by Inscricao, LA.Codigo asc";
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
            <h3 class="card-title">Devolução de contratos</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                      <th>INSCRICAO</th>
                      <th>ASSOCIADO</th>
                      <th>GRUPO</th>
                      <th>SUBGRUPO</th>
                      <th>VENDEDOR</th>
                      <th>SUPERVISOR</th>
                      <th>COORDENADOR</th>
                      <th>CIDADE</th>
                      <th>UF</th>
                      <th>DATA</th>
                      <th>STATUS</th>
                      <th>MOTIVO</th>
                      <th>USUARIO</th>
                  </tr>
                </thead>
                <tbody>
				<?php
					foreach($resultado as $r) {
						//<i class="fas fa-search-dollar"></i>
				?>
					
					<tr>
						<td><?php echo $r['Inscricao']; ?></td>
						<td><?php echo $r['Associado']; ?></td>
            <td><?php echo $r['Grupo']; ?></td>
            <td><?php echo $r['SubGrupo']; ?></td>
						<td><?php echo $r['Vendedor']; ?></td>
						<td><?php echo $r['Supervisor']; ?></td>
						<td><?php echo $r['Coordenador']; ?></td>
            <td><?php echo $r['Cidade']; ?></td>
            <td><?php echo $r['UF']; ?></td>
            <td><?php echo $r['DataEvento']; ?></td>
            <td><?php echo $r['Status']; ?></td>
            <td><?php echo $r['Motivo']; ?></td>
            <td><?php echo $r['Usuario']; ?></td>
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

