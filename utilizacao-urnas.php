<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

//Verificação de chamada direta
$id_relatorio = 9;
require "chamada.php"; 

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
$planilha = "gerar-utilizacao-urnas.php?datai=$datainicio&dataf=$datafim";	
$res_query = "SELECT FunerObito.Lancamento LANCAMENTO, 
       FunerPed.Falecido FALECIDO, 
	   Funerarias.nome FUNERARIA, 
	   FunerGrupoObito.Descricao LABORATORIO, 
	   CONVERT(VARCHAR(10), FunerPed.DataFalecimento, 103) AS DATA_FALECIMENTO, 
	   FunerObito.CIDADE_F CIDADE_FALECIMENTO,
	   FunerPed.numOrdem ORDEM_SERVICO, 
	   CONVERT(VARCHAR(10), FunerPed.Data, 103) AS DATA_OS,
	   FunerPed.Obito TIPO_OBITO, 
	   FunerPedItens.Codigo CODIGO, 
	   FunerProdutos.Descricao DESCRICAO_PRODUTO,
	   FunerPedItens.Qtde QUANTIDADE, 
	   FunerPedItens.ValorUnit VALOR_UNITARIO, 
	   FunerPedItens.ValorTot VALOR_TOTAL
FROM 
	FunerPed
	left outer join FunerObito ON FunerPed.NumObito = funerobito.lancamento and funerped.funeraria = funerobito.funeraria
	inner join funerarias ON funerPed.funeraria = funerarias.funeraria
	left outer join FunerGrupoObito ON FunerGrupoObito.codigo = funerped.codigoFunerLaboratorio
	left outer join funerPedItens ON FunerPedItens.funeraria = funerped.funeraria and funerpeditens.numordem = funerped.NumOrdem 
	inner join FunerProdutos ON FunerProdutos.codigo = funerpeditens.codigo and funerprodutos.funeraria = funerpeditens.funeraria
WHERE
	FunerPed.DataFalecimento BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.999' and
	FunerProdutos.Grupo = 1 and FunerProdutos.SubGrupo in (2,3,4,27,31)
ORDER BY 
	FunerPed.Funeraria, FunerPed.Numordem";
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
            <h3 class="card-title">Relatório de Utilização de Urnas</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
					<th>LANÇAMENTO</th>
					<th>FALECIDO</th>
					<th>FUNERARIA</th>
					<th>LABORATORIO</th>
					<th>DATA DO FALECIMENTO</th>
					<th>CIDADE DO FALECIMENTO</th>
					<th>ORDEM DE SERVIÇO</th>
					<th>DATA OS</th>
					<th>TIPO DE OBITO</th>
					<th>CÓDIGO</th>
					<th>DESCRIÇÃO DO PRODUTO</th>
					<th>QUANTIDADE</th>
					<th>VALOR UNITÁRIO</th>
					<th>VALOR TOTAL</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['LANCAMENTO']; ?></td>
						<td><?php echo $r['FALECIDO']; ?></td>
						<td><?php echo $r['FUNERARIA']; ?></td>
						<td><?php echo $r['LABORATORIO']; ?></td>
						<td><?php echo $r['DATA_FALECIMENTO']; ?></td>	
						<td><?php echo $r['CIDADE_FALECIMENTO']; ?></td>	
						<td><?php echo $r['ORDEM_SERVICO']; ?></td>	
						<td><?php echo $r['DATA_OS']; ?></td>	
						<td><?php echo $r['TIPO_OBITO']; ?></td>	
						<td><?php echo $r['CODIGO']; ?></td>	
						<td><?php echo $r['DESCRICAO_PRODUTO']; ?></td>	
						<td><?php echo number_format ($r['QUANTIDADE'], 0,',','.'); ?></td>	
						<td><?php echo number_format ($r['VALOR_UNITARIO'], 2,',','.'); ?></td>
						<td><?php echo number_format ($r['VALOR_TOTAL'], 2,',','.'); ?></td>
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