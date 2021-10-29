<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

$numero  = $_GET['numero'];
require_once "conn/conexao_mssql_zelo.php";

$res_query = "
              select convert( varchar(20),trr.tblretorno) as ID_RETORNO
                  , tr.arquivo AS ARQUIVO_RETORNO
                  , trr.texto as TEXTO
                  , trr.ocorrencia as OCORRENCIA
                  , trr.inscricao as CONTRATO
                  , trr.referencia as REFERENCIA
                  , CONCAT( CONVERT(VARCHAR(10), trr.datahoracadastro, 103), ' - ', CONVERT(VARCHAR(8), trr.datahoracadastro, 108) ) AS HORABAIXA
              from tblretornorelatorio as trr
              inner join tblretorno as tr
              on tr.codigo = trr.tblretorno
              where trr.numero = '$numero'

              UNION ALL

              select
              'N/A' ID_RETORNO
              , NomeArquivo AS ARQUIVO_RETORNO
              , IIF (TipoBaixa = 'Baixada com sucesso', CONCAT('Baixa OK >>> ', Inscricao, ' ', Referencia, ' N:', NossoNumero, ' ', CONVERT(VARCHAR(10), DataPagamento, 103),' Valor: ', ValorTitulo, ' Val PG: ', ValorTitulo), IIF (TipoBaixa = 'Baixa OK', CONCAT('Baixa OK >>> ', Inscricao, ' ', Referencia, ' N:', NossoNumero, ' ', CONVERT(VARCHAR(10), DataPagamento, 103),' Valor: ', ValorTitulo, ' Val PG: ', ValorTitulo), 'N/A')) AS TEXTO
              , TipoBaixa AS	OCORRENCIA
              , Inscricao AS CONTRATO
              , Referencia AS REFERENCIA
              , CONCAT( CONVERT(VARCHAR(10), DataBaixa, 103), ' - ', CONVERT(VARCHAR(8), DataBaixa, 108) ) AS HORABAIXA
              From LogBaixaBanco where NossoNumero = '$numero'
            ";

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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
            <h3 class="card-title">Histórico</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>ARQUIVO</th>
                  <th>DESCRIÇÃO</th>
                  <th>OCORRENCIA</th>
                  <th>CONTRATO</th>
                  <th>REFERENCIA</th>
                  <th>DATA</th>
                </tr>
                </thead>
                <tbody>
				<?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo strtoupper($r['ID_RETORNO']); ?></td>
						<td><?php echo strtoupper($r['ARQUIVO_RETORNO']); ?></td>
						<td><?php echo strtoupper($r['TEXTO']); ?></td>
						<td><?php echo strtoupper($r['OCORRENCIA']); ?></td>
						<td><?php echo strtoupper($r['CONTRATO']); ?></td>
						<td><?php echo strtoupper($r['REFERENCIA']); ?></td>
						<td><?php echo strtoupper($r['HORABAIXA']); ?></td>
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

