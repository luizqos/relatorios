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
if (!empty($_POST["inscricao"])) {
  $inscricao = $_POST ["inscricao"];
 }else
 {
  $inscricao = 0;
 }
 if (!empty($_POST["referencia"])) {
  $referencia = $_POST ["referencia"];
 }else
 {
  $referencia = 0;
 }
$autorizacao = $_POST ["autorizacao"];
$nsucv = $_POST ["nsucv"];
$planilha = "#";
$res_query = "  
                SELECT distinct P.Inscricao
                    , P.Referencia
                    , CONVERT(VARCHAR(10), P.Vencimento, 103) AS Vencimento
                    , P.Valor
                    , CONVERT(VARCHAR(10), P.Pagamento, 103) AS Pagamento
                    , P.ValorPago
                    , E.Usuario
                    , E.TipoDoc
                    , CONCAT( CONVERT(VARCHAR(10), E.DataHora, 103), ' - ', CONVERT(VARCHAR(5), E.DataHora, 108) ) AS DataBaixa
                    , B.Descricao AS Bandeira
                    , C.Autorizacao
                    , C.SeqBaixa
                    , C.NSUCV
                    , U.Nick AS UsuarioInsert
                    , CONCAT( CONVERT(VARCHAR(10), C.DataInsert, 103), ' - ', CONVERT(VARCHAR(5), C.DataInsert, 108) ) AS DataInsert
                FROM vwParcela AS P

                INNER JOIN CaixaCartao AS C
                ON P.seqbaixa = C.SeqBaixa

                INNER JOIN Extrato AS E
                ON P.seqbaixa = E.SeqBaixa

                INNER JOIN Bandeiras AS B
                ON C.ID_Bandeiras = B.ID

                INNER JOIN Usuarios AS U
                ON C.UsuInsert = U.Codigo
                WHERE (P.Inscricao = $inscricao AND p.Referencia LIKE '%$referencia') OR (C.Autorizacao = '$autorizacao' OR C.NSUCV = '$nsucv')
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
            <h3 class="card-title">Relatório de Baixas por Cartão</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>INSCRICAO</th>
					<th>REFERENCIA</th>
					<th>VENCIMENTO</th>
					<th>VALOR</th>
					<th>PAGAMENTO</th>
					<th>VALOR PAGO</th>
					<th>USUARIO DE BAIXA</th>
					<th>TIPO DOC.</th>
					<th>DATA DA BAIXA</th>
                    <th>SEQ. DA BAIXA</th>
					<th>BANDEIRA</th>
					<th>AUTORIZAÇÃO</th>
					<th>NSU/CV</th>
					<th>USUÁRIO</th>
					<th>DATA</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['Inscricao']; ?></td>
                        <td><?php echo $r['Referencia']; ?></td>
                        <td><?php echo $r['Vencimento']; ?></td>
                        <td><?php 
								if ($r['Valor']>0){
									echo number_format ($r['Valor'], 2,',','.');
								}else{
									echo $r['Valor'];
								}
							?>
						</td>
                        <td><?php echo $r['Pagamento']; ?></td>
                        <td><?php 
								if ($r['ValorPago']>0){
									echo number_format ($r['ValorPago'], 2,',','.');
								}else{
									echo $r['ValorPago'];
								}
							?>
						</td>
						<td><?php echo strtoupper($r['Usuario']); ?></td>
                        <td><?php echo strtoupper($r['TipoDoc']); ?></td>
						<td><?php echo $r['DataBaixa']; ?></td>
                        <td><?php echo $r['SeqBaixa']; ?></td>
						<td><?php echo strtoupper($r['Bandeira']); ?></td>
						<td><?php echo $r['Autorizacao']; ?></td>
						<td><?php echo $r['NSUCV']; ?></td>
						<td><?php echo strtoupper($r['UsuarioInsert']); ?></td>
						<td><?php echo $r['DataInsert']; ?></td>
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