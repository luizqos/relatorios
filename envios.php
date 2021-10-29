<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

$inscricao  = $_GET['inscricao'];
require_once "conn/conexao_mssql_zelo.php";

$res_query = "
select  A.inscricao INSCRICAO
				--, IIF (A.ASSTIPOCOBRANCA = 7, 'COBRANÇA RECORRENTE', IIF(A.ASSTIPOCOBRANCA = 1, 'BOLETO', CONVERT(VARCHAR(10),A.ASSTIPOCOBRANCA))) AS TIPOCOBRANCA
				--, A.payzentokencard PTK
				, A.nome NOME
				--, A.CPF CPF
				, S.TelefoneEnvio TELEFONE
				, S.EmailEnvio EMAIL
				, CONCAT('https://contrato.grupozelo.com/#/home/', S.HashLink) AS LINK
				, CONCAT(S.UsuarioEnvio, '-',U.Nome) AS USUARIO
				, IIF (S.FormaEnvio = 1, 'SMS', IIF(S.FormaEnvio = 2, 'EMAIL', IIF(S.FormaEnvio = 3, 'WPP', ''))) AS FORMAENVIO
				, IIF (S.statuslink = 1, 'PENDENTE', IIF(S.statuslink = 2, 'CONCLUIDO', 'CANCELADO')) STATUS
				, S.TipoAceite TIPOACEITE
				, IIF(S.StatusLink = 2, 'S', 'N') AS ACEITE
				, CONCAT( CONVERT(VARCHAR(10), S.UpdateAt, 103), ' - ', CONVERT(VARCHAR(8), (S.UpdateAt - 0.125), 108) ) AS DATAACEITE
				, CONCAT( CONVERT(VARCHAR(10), S.CreateAt, 103), ' - ', CONVERT(VARCHAR(8), S.CreateAt, 108) ) AS DATACONTRATO
				, IIF (S.TipoAceite = 1, 'ACEITE', IIF(S.TipoAceite = 2, 'ACEITE + ADESÃO', IIF(S.TipoAceite = 3, 'ACEITE + ADESÃO + COBRANÇA CONTINUADA', ' - '))) AS DESCRICAO
				from Associados as A
				INNER JOIN signatureorderlink AS S ON S.Inscricao = A.Inscricao
				LEFT OUTER JOIN Usuarios AS U ON S.UsuarioEnvio = U.Codigo
				where A.Inscricao = '$inscricao'
				order by s.CreateAt desc
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
					<th>INSCRICAO</th>
					<th>NOME</th>
					<th>TELEFONE</th>
					<th>EMAIL</th>
					<th>LINK</th>
          			<th>ENVIO</th>
					<th>USUARIO</th>
					<th>STATUS ENVIO</th>
					<th>TIPO ACEITE</th>
					<th>ACEITE</th>
					<th>DATA ACEITE</th>
					<th>ENVIO</th>
					<th>DESCRICAO</th>
                </tr>
                </thead>
                <tbody>
				<?php
					foreach($resultado as $r) {
						if ($r['FORMAENVIO'] == 'WPP')
						{
							$icone = 'fab fa-whatsapp-square';
							$cor = 'green';
						}else
						if ($r['FORMAENVIO'] == 'SMS')
						{
							$icone = 'fas fa-sms';
							$cor = 'purple';
						}
						else
						if ($r['FORMAENVIO'] == 'EMAIL')
						{
							$icone = 'fas fa-envelope';
							$cor = 'orange';
						}
				?>
						<tr>
							<td><?php echo $r['INSCRICAO']; ?></td>
							<td><?php echo strtoupper($r['NOME']); ?></td>
							<td><?php echo strtoupper($r['TELEFONE']); ?></td>
							<td><?php echo strtolower($r['EMAIL']); ?></td>
							<td style="font-size: 1.5em"><a href="<?php echo $r['LINK']; ?>" target="_blank"><i class="fas fa-link"></i></a></td>
							<td style="font-size: 2.5em; color: <?php echo $cor; ?>;"><i class="<?php echo $icone; ?>"></i></td>
              <td><?php echo strtoupper($r['USUARIO']); ?></td>
							<td><?php echo $r['STATUS']; ?></td>
							<td><?php echo $r['TIPOACEITE']; ?></td>
							<td><?php echo $r['ACEITE']; ?></td>
							<td><?php echo $r['DATAACEITE']; ?></td>
							<td><?php echo $r['DATACONTRATO']; ?></td>
							<td><?php echo strtoupper($r['DESCRICAO']); ?></td>
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

