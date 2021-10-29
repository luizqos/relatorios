<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

//Verificação de chamada direta
$id_relatorio = 8;
require "chamada.php"; 

if ($total < 1){
	header('Location: /relatorios/card.php');
}

function converteData($data){
  if(count(explode("/",$data)) > 1): 
       return implode("-",array_reverse(explode("/",$data)));
  elseif(count(explode("-",$data)) > 1): 
       return implode("/",array_reverse(explode("-",$data)));
  endif;
}

$banco  = $_POST ["banco"];
$datainicio  = converteData($_POST ["datainicio"]);
$planilha = "#";
// Dados do banco


$server = 'gold.grupozelo.net';
$port = '1443'; // porta padrão
$server = $port !== '1443' && is_string($port) ? $server .= ", $port": $server;
$database = 'ZELO';
$user = 'sa';
$pass = '071999gs';


$conninfo = array("Database" => $database, "UID" => $user, "PWD" => $pass);
$conn = sqlsrv_connect($server, $conninfo);

$instrucaoSQL = "
                  select codigo 
                  from tblretorno 
                  where boletoconta in (3,4,11,12) 
                  and datahoracadastro between '$datainicio 00:00:00.000' and '$datainicio 23:59:59.999'
                  --and datahoracadastro >= '$datainicio 00:00:00.000' 
                ";

$params = array();
$options =array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$result = sqlsrv_query($conn, $instrucaoSQL, $params, $options);

for ($i = 1; $i <= sqlsrv_num_rows($result); ++$i)
  {
    $line = sqlsrv_fetch_array($result);
    $cod[$i] = $line[0];
  }


  $res_query = "
  select top 1 tblretorno, tblretorno.arquivo, tblretorno.baixado, (select COUNT(*) from tblretornorelatorio where tblretorno = $cod[1]) as processado, (select top 1 numero from tblretornorelatorio where tblretorno = $cod[1] order by datahoracadastro desc) as numero,
  CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[1] order by datahoracadastro desc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[1] order by datahoracadastro desc), 108) ) as ultima_execucao
  ,CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[1] order by datahoracadastro asc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[1] order by datahoracadastro asc), 108) ) as primeira_execucao
   from tblretornorelatorio 
  inner join tblretorno on tblretorno.codigo = tblretornorelatorio.tblretorno
  where tblretorno in ($cod[1])

  union all

  select top 1 tblretorno, tblretorno.arquivo, tblretorno.baixado, (select COUNT(*) from tblretornorelatorio where tblretorno = $cod[2]) as processado, (select top 1 numero from tblretornorelatorio where tblretorno = $cod[2] order by datahoracadastro desc) as numero,
  CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[2] order by datahoracadastro desc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[2] order by datahoracadastro desc), 108) ) as ultima_execucao
  ,CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[2] order by datahoracadastro asc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[2] order by datahoracadastro asc), 108) ) as primeira_execucao
  from tblretornorelatorio 
  inner join tblretorno on tblretorno.codigo = tblretornorelatorio.tblretorno
  where tblretorno in ($cod[2])

  union all

  select top 1 tblretorno, tblretorno.arquivo, tblretorno.baixado, (select COUNT(*) from tblretornorelatorio where tblretorno = $cod[3]) as processado, (select top 1 numero from tblretornorelatorio where tblretorno = $cod[3] order by datahoracadastro desc) as numero,
  CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[3] order by datahoracadastro desc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[3] order by datahoracadastro desc), 108) ) as ultima_execucao
  ,CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[3] order by datahoracadastro asc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[3] order by datahoracadastro asc), 108) ) as primeira_execucao
  from tblretornorelatorio 
  inner join tblretorno on tblretorno.codigo = tblretornorelatorio.tblretorno
  where tblretorno in ($cod[3])
  
  union all

  select top 1 tblretorno, tblretorno.arquivo, tblretorno.baixado, (select COUNT(*) from tblretornorelatorio where tblretorno = $cod[4]) as processado, (select top 1 numero from tblretornorelatorio where tblretorno = $cod[4] order by datahoracadastro desc) as numero,
  CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[4] order by datahoracadastro desc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[4] order by datahoracadastro desc), 108) ) as ultima_execucao
  ,CONCAT( CONVERT(VARCHAR(10), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[4] order by datahoracadastro asc), 103), ' - ', CONVERT(VARCHAR(8), (select top 1 datahoracadastro from tblretornorelatorio where tblretorno = $cod[4] order by datahoracadastro asc), 108) ) as primeira_execucao
  from tblretornorelatorio 
  inner join tblretorno on tblretorno.codigo = tblretornorelatorio.tblretorno
  where tblretorno in ($cod[4])
"; 

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
            <h3 class="card-title">Relatório de Baixas</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
				<tr>
					<th>CODIGO</th>
          <th>ARQUIVO</th>
					<th>BAIXADO</th>
					<th>PROCESSADO</th>
          <th>NOSSO NUMERO</th>
					<th>INICIO</th>
          <th>FIM</th>
				</tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['tblretorno']; ?></td>
            <td><?php echo strtoupper($r['arquivo']); ?></td>
						<td><?php echo strtoupper($r['baixado']); ?></td>
						<td><?php echo strtoupper($r['processado']); ?></td>
            <td><?php echo strtoupper($r['numero']); ?></td>
            <td><?php echo strtoupper($r['primeira_execucao']); ?></td>
						<td><?php echo strtoupper($r['ultima_execucao']); ?></td>
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