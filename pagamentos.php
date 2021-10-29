<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

$inscricao  = $_GET['inscricao'];
require_once "conn/conexao_mssql_zelo.php";

$res_query = "
              select P.Inscricao
              , P.Referencia
              , CONVERT(varchar(10), P.Vencimento, 103) AS Vencimento
              , P.Vencimento as DTA_VENC
              , P.Valor
              , CONVERT(varchar(10), P.Pagamento, 103) AS Pagamento
              , P.ValorPago
              , P.NumBoleto  
              , IIF(P.Pagamento IS NULL, '', E.Usuario) AS Usuario
              , E.TipoDoc
              , IIF(P.Pagamento IS NULL, '', CONCAT(CONVERT(VARCHAR(10), E.DataHora, 103), ' - ', CONVERT(VARCHAR(8), E.DataHora, 108))) AS HoraBaixa
                            ,OrigemPagtoPayzen = CASE
                                WHEN E.Usuario = 'payzen' AND E.TipoDoc = 'CRTCR' AND E.Referencia = '1'
                                THEN 'BOLETO PAYZEN'
                                WHEN E.Usuario = 'payzen' AND E.TipoDoc = 'CRTCR' AND E.Referencia not in ('1')
                                THEN 'AREA DO CLIENTE'
                                WHEN E.Usuario = 'payzen'
                                THEN 'CARTÃO RECORRENTE'
                                ELSE ''
                              END
              from Mensalidade as P
              left join Extrato as E on CONCAT(P.Inscricao,'-',P.Referencia) = CONCAT(E.Inscricao,'-',E.Referencia)
              where P.Inscricao = $inscricao
              UNION ALL   
              select I.Inscricao
              , CONVERT(varchar(2), I.Parcela) AS Referencia
              , CONVERT(varchar(10), I.Vencimento, 103) AS Vencimento
              , I.Vencimento as DTA_VENC
              , I.Valor
              , CONVERT(varchar(10), I.Pagamento, 103) AS Pagamento
              , I.ValorPago
              , I.NumBoleto  
              , IIF(I.Pagamento IS NULL, '', E.Usuario) AS Usuario
              , E.TipoDoc
              , IIF(I.Pagamento IS NULL, '', CONCAT(CONVERT(VARCHAR(10), E.DataHora, 103), ' - ', CONVERT(VARCHAR(8), E.DataHora, 108))) AS HoraBaixa
                            ,OrigemPagtoPayzen = CASE
                                WHEN E.Usuario = 'payzen' AND E.TipoDoc = 'CRTCR' AND E.Referencia = '1'
                                THEN 'BOLETO PAYZEN'
                                WHEN E.Usuario = 'payzen' AND E.TipoDoc = 'CRTCR' AND E.Referencia not in ('1')
                                THEN 'AREA DO CLIENTE'
                                WHEN E.Usuario = 'PAYZEN' AND E.TipoDoc = 'CAIXA'
                                THEN 'CARTÃO RECORRENTE'
                                WHEN E.Usuario = 'payzen'
                                THEN 'CARTÃO RECORRENTE'
                                ELSE ''
                              END
              from Inscricao as I
              left join Extrato as E on CONCAT(I.Inscricao,'-',I.Parcela) = CONCAT(E.Inscricao,'-',E.Referencia)
              where I.Inscricao = $inscricao
              order by DTA_VENC asc
        ;
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
					<th>REF.</th>
					<th>VENCIMENTO</th>
					<th>VALOR</th>
					<th>PAGAMENTO</th>
					<th>VALOR PAGO</th>
					<th>NOSSO NUMERO</th>
					<th>USUARIO</th>
					<th>TIPO DOC.</th>
					<th>BAIXA EM:</th>
					<th>ORIGEM PGTO PAYZEN</th>
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
                            <td><?php echo number_format ($r['Valor'], 2,',','.'); ?></td>
							<td><?php echo $r['Pagamento']; ?></td>
                            <td><?php 
									if ($r['ValorPago']>0){
										echo number_format ($r['ValorPago'], 2,',','.');
									}else{
										echo $r['ValorPago'];
									}
								?>
							</td>
                            <td><?php echo $r['NumBoleto']; ?></td>
							<td><?php echo $r['Usuario']; ?></td>
							<td><?php echo $r['TipoDoc']; ?></td>
							<td><?php echo $r['HoraBaixa']; ?></td>
							<td><?php echo $r['OrigemPagtoPayzen']; ?></td>
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

