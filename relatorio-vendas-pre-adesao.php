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
$planilha = "gerar-vendas-pre-adesao.php?datai=$datainicio&dataf=$datafim";
$res_query = "SELECT A.Inscricao
, A.Grupo
, A.SubGrupo
, A.TipoVenda
, V.Nome AS Vendedor
, S.Nome AS Supervisor
, A.Nome
, I.Valor
, CONVERT(VARCHAR(10), I.Vencimento, 103) AS Vencimento
, CONVERT(VARCHAR(10), I.Pagamento, 103) AS Pagamento
, TC.TipCobDescricao AS Cobranca
, A.Aceite
, A.TipoAceite
, CONVERT(VARCHAR(10), A.DataAceite, 103) AS DataAceite
FROM associados AS A
INNER JOIN Inscricao AS I ON I.Inscricao = A.Inscricao
LEFT JOIN TipoCobranca AS TC ON TC.TipCobCodigo = A.AssTipoCobranca
LEFT JOIN Vendedores AS V ON V.Codigo = A.Vendedor
LEFT JOIN Supervisores AS S ON S.Codigo = A.Supervisor
WHERE A.Grupo IN ('PRE','ZELO', 'ZP')
AND A.Aceite = 'N'
AND A.LinkVenda IS NOT NULL
AND I.Pagamento IS NOT NULL
--AND A.SubGrupo NOT IN ('ZP', 'ZPE')
AND (I.Vencimento BETWEEN '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999')";
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
            <h3 class="card-title">Relatório de Vendas Pre - Por data de Adesão</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
                <tr>
					<th>INSCRICAO</th>
					<th>CLIENTE</th>
					<th>GRUPO</th>
					<th>SUBGRUPO</th>
					<th>TIPO DE VENDA</th>
					<th>VENDEDOR</th>
					<th>SUPERVISOR</th>
					<th>VALOR PAGO</th>
					<th>VENC. ADESÃO</th>
					<th>PAGTO. ADESÃO</th>
					<th>TIPO DE COBRANÇA</th>
					<th>ACEITE</th>
					<th>TIPO ACEITE</th>
					<th>DATA DO ACEITE</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['Inscricao']; ?></td>
						<td><?php echo strtoupper($r['Nome']); ?></td>
						<td><?php echo strtoupper($r['Grupo']); ?></td>
						<td><?php echo $r['SubGrupo']; ?></td>
						<td><?php echo strtoupper($r['TipoVenda']); ?></td>
						<td><?php echo strtoupper($r['Vendedor']); ?></td>
						<td><?php echo strtoupper($r['Supervisor']); ?></td>
						<td><?php echo number_format ($r['Valor'], 2,',','.'); ?></td>
						<td><?php echo $r['Vencimento']; ?></td>
						<td><?php echo $r['Pagamento']; ?></td>
						<td><?php echo strtoupper($r['Cobranca']); ?></td>
						<td><?php echo $r['Aceite']; ?></td>
						<td><?php echo $r['TipoAceite']; ?></td>
						<td><?php echo $r['DataAceite']; ?></td>
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