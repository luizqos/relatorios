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
$planilha = "gerar-vendas-sem-aceite.php?datai=$datainicio&dataf=$datafim";
$res_query = "SELECT DISTINCT
	Associados.Inscricao AS INSCRICAO,
	Associados.NOME AS CLIENTE,
	Vendedores.Nome AS VENDEDOR,
	Coordenadores.Nome AS COORDENADOR,
	CONVERT(VARCHAR(10), Associados.Data, 103) AS DATA_CADASTRO,
	Associados.GRUPO AS GRUPO,
	Associados.Aceite AS ACEITE,
	Associados.TipoAceite AS TIPO_ACEITE,
	Inscricao.Valor AS VALOR_INSCRICAO,
	CONVERT(VARCHAR(10), Inscricao.Pagamento, 103) AS PGTO_INSCRICAO,
	IIF((Extrato.DataHora IS NULL), '', (CONVERT(VARCHAR(10),(select DataHora from Extrato WHERE Referencia IN ('1', '01') and Inscricao = Associados.Inscricao), 103))) AS DATA_BAIXA,
	IIF((Mensalidade.Valor <> NULL), '', (CONVERT(VARCHAR(10),(SELECT TOP 1 VALOR FROM Mensalidade WHERE Inscricao = Associados.Inscricao ORDER BY VENCIMENTO DESC)))) AS VALOR_MENSALIDADE
FROM associados
LEFT JOIN Vendedores ON VENDEDORES.Codigo = Associados.Vendedor
LEFT JOIN Coordenadores ON Coordenadores.Codigo = Associados.Coordenador
LEFT JOIN Inscricao ON Inscricao.Inscricao = Associados.Inscricao
LEFT JOIN Extrato ON Extrato.Inscricao = Associados.Inscricao
LEFT JOIN Mensalidade ON Mensalidade.Inscricao = Associados.Inscricao
INNER JOIN STATUS ON Associados.Status = STATUS.Codigo
WHERE Associados.Data between '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999'
AND Inscricao.Pagamento IS NOT NULL
AND Associados.TipoAceite = 1
AND Associados.Aceite = 'N'
GROUP BY Mensalidade.Valor, Associados.Inscricao, Associados.Nome, Vendedores.Nome, Coordenadores.Nome, Associados.Data, Associados.TipoAceite, Associados.DataAceite, Inscricao.Valor, extrato.TipoDoc, Inscricao.Pagamento, Associados.Aceite, Extrato.DataHora, Associados.Grupo
ORDER BY Associados.nome
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
            <h3 class="card-title">Relatório de Vendas sem Aceite com Adesão Paga</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>INSCRICAO</th>
					<th>CLIENTE</th>
					<th>VENDEDOR</th>
					<th>COORDENADOR</th>
					<th>DATA DE CADASTRO</th>
					<th>GRUPO</th>
					<th>ACEITE</th>
					<th>TIPO DE ACEITE</th>
					<th>VALOR DA INSCRIÇÃO</th>
					<th>PAGAMENTO DA INSCRIÇÃO</th>
					<th>DATA DA BAIXA</th>
					<th>VALOR MENSALIDADE</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['INSCRICAO']; ?></td>
						<td><?php echo strtoupper($r['CLIENTE']); ?></td>
						<td><?php echo strtoupper($r['VENDEDOR']); ?></td>
						<td><?php echo strtoupper($r['COORDENADOR']); ?></td>
						<td><?php echo $r['DATA_CADASTRO']; ?></td>
						<td><?php echo strtoupper($r['GRUPO']); ?></td>
						<td><?php echo $r['ACEITE']; ?></td>
						<td><?php echo $r['TIPO_ACEITE']; ?></td>
						<td><?php 
								if ($r['VALOR_INSCRICAO']>0){
									echo number_format ($r['VALOR_INSCRICAO'], 2,',','.');
								}else{
									echo $r['VALOR_INSCRICAO'];
								}
							?>
						</td>
						<td><?php echo $r['PGTO_INSCRICAO']; ?></td>
						<td><?php echo $r['DATA_BAIXA']; ?></td>
						<td><?php 
								if ($r['VALOR_MENSALIDADE']>0){
									echo number_format ($r['VALOR_MENSALIDADE'], 2,',','.');
								}else{
									echo $r['VALOR_MENSALIDADE'];
								}
							?>
						</td>
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