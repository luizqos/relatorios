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
$datafim  = converteData($_POST ["datafim"]);	
$planilha = "gerar-parcelas-quitadas.php?datai=$datainicio&dataf=$datafim";
$res_query = "select A.inscricao INSCRICAO
, A.nome NOME
,U.Nome USUARIO

,IIF((A.UsuAtivouRecorrencia IS NOT NULL), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuAtivouRecorrencia
WHERE Associados.INSCRICAO = A.inscricao), (SELECT Usuarios.Nome FROM ASSOCIADOS
INNER JOIN Usuarios
ON  Usuarios.Codigo = Associados.UsuInsert
WHERE Associados.INSCRICAO = A.inscricao)) AS USUARIO_REC

, CONVERT(VARCHAR(10), O.data_cadastro, 103) DATA_CADASTRO, O.status STATUS
, O.subscriptionId SUBSCRIPITONID
, E.ValorPago VALORPAGO
, CONVERT(VARCHAR(10), M.Vencimento, 103) VENCIMENTO
, CONVERT(VARCHAR(10), E.Pagamento, 103) DATAPAGAMENTO
	--  A.SubGrupo SubGrupo,
	--CONVERT(VARCHAR(10), A.DataUpdate, 103) Data,
from associados AS A
inner join orderRequest AS O 
on A.inscricao = O.inscricao

inner join usuarios AS U 
on A.UsuUpdate = U.Codigo

left join extrato AS E on 
A.inscricao = E.inscricao

inner join mensalidade AS M
on E.inscricao = M.inscricao and E.Pagamento = M.Vencimento

where A.AssTipoCobranca = 7 and A.inscricao not in(21500) 
and E.Usuario like '%payzen%' 
and M.pagamento is not null
and O.status = 'A' 
and U.nome not in('ANA LUISA DUARTE', 'ROBERTA FERNANDA NUNES')
and M.pagamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.999'
--and A.DATA between '$datainicio' and (getdate() + 1)
--and (A.DataUpdate >= O.data_cadastro) 
and ((CONVERT(VARCHAR(10), A.DataUpdate, 103) = CONVERT(VARCHAR(10), O.data_cadastro, 103)) or (A.DataUpdate > O.data_cadastro))
--group by A.inscricao, A.nome, A.DataUpdate, U.nome, O.status, O.data_cadastro, O.subscriptionId, E.inscricao
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
            <h3 class="card-title">Relatório de Parcelas Quitadas</h3></br>
            <h1 class="card-title"><b>Período: </b><?php echo $_POST ["datainicio"]; ?> a <?php echo $_POST ["datafim"]; ?></h1>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
				<tr>
					<th>INSCRICAO</th>
					<th>NOME</th>
					<th>USUARIO</th>
					<th>USUARIO RECORRENTE</th>
					<th>DATA DE CADASTRO</th>
					<th>STATUS</th>
					<th>SUBSCRIPITONID</th>
					<th>VALOR PAGO</th>
					<th>VENCIMENTO</th>
					<th>DATA DO PAGAMENTO</th>
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
						<td><?php echo strtoupper($r['USUARIO_REC']); ?></td>
						<td><?php echo $r['DATA_CADASTRO']; ?></td>
						<td><?php echo strtoupper($r['STATUS']); ?></td>
						<td><?php echo $r['SUBSCRIPITONID']; ?></td>
						<td><?php echo number_format ($r['VALORPAGO'], 2,',','.'); ?></td>
						<td><?php echo $r['VENCIMENTO']; ?></td>
						<td><?php echo $r['DATAPAGAMENTO']; ?></td>
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