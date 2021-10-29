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
$planilha = "#";

$res_query = "select A.inscricao as INSCRICAO
, A.nome NOME
,IIF((U.Nome = 'GOLD'), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuInsert
WHERE Associados.INSCRICAO = A.inscricao), U.Nome) as USUARIO
,CONVERT(VARCHAR(10), O.data_cadastro, 103) DATA_CADASTRO
, O.status STATUS
--, O.subscriptionId SUBSCRIPITONID
, max(M.Valor) VALORMENSALIDADE

from associados AS A
inner join orderRequest AS O 
on A.inscricao = O.inscricao

inner join usuarios AS U 
on A.UsuUpdate = U.Codigo

inner join mensalidade AS M 
on A.inscricao = M.inscricao

where A.AssTipoCobranca = 7 and A.inscricao not in(21500) 
--and E.Usuario like '%payzen%'
and O.status = 'A' and U.nome not in('ANA LUISA DUARTE', 'ROBERTA FERNANDA NUNES')
--and O.data_cadastro > '2019-10-01 00:00:00.000'
and a.Grupo not in ('ZP')
--and CONVERT(VARCHAR(10), A.DataUpdate, 103) = CONVERT(VARCHAR(10), O.data_cadastro, 103)
group by A.inscricao, A.nome, A.DataUpdate, U.nome, O.status, O.data_cadastro, O.subscriptionId
order by O.data_cadastro asc";
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
            <h3 class="card-title">Relatório de Vendas com Recorrencia Ativa</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
				<tr>
					<th>INSCRICAO</th>
					<th>NOME</th>
					<th>USUARIO</th>
					<th>DATA DE CADASTRO</th>
					<th>STATUS</th>
					<th>VALOR DA MENSALIDADE</th>
				</tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['INSCRICAO']; ?></td>
						<td><?php echo strtoupper($r['NOME']); ?></td>
						<td><?php echo strtoupper($r['USUARIO']); ?></td>
						<td><?php echo $r['DATA_CADASTRO']; ?></td>
						<td><?php echo $r['STATUS']; ?></td>
						<td><?php echo number_format ($r['VALORMENSALIDADE'], 2,',','.'); ?></td>
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
