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
$planilha = "gerar-relatorio-vendas-pos-vendas.php?datai=$datainicio&dataf=$datafim";
$res_query = "
              SELECT DISTINCT
              A.Inscricao AS INSCRICAO
              , S.descricao AS STATUS
              , A.AssTipoCobranca AS TIPO_COBRANCA
              , A.TipoAceite AS TIPO_ACEITE
              , A.Aceite AS ACEITE
              , A.Grupo AS GRUPO
              , A.SubGrupo AS SUBGRUPO
              , A.TipoVenda AS TIPOVENDA
              , A.Vendedor AS VENDEDOR
              , IIF ((SELECT TOP 1 status FROM orderRequest WHERE inscricao  = A.Inscricao AND status = 'A') = 'A', 'SIM', 'NÃO') AS RECORRENTE
              ,ORIGEM_VENDA = CASE
                  WHEN A.Coordenador = '503'
                  THEN 'TLMK'
                  WHEN A.Coordenador = 'ROB' OR A.Grupo IN ('ZP', 'ZPE')
                  THEN 'PRIME'
                  WHEN A.Vendedor = 'EC0' OR A.Grupo = 'SITE'
                  THEN 'SITE'
                  WHEN A.Vendedor = 'UST'
                  THEN 'SANTA LUZIA'
                  ELSE 'EXTERNO'
                END
              , CONVERT(varchar(10), IIF(A.DataInsert IS NULL, A.DATA, A.DataInsert), 103) AS DATA_VENDA
              , CONVERT(varchar(10), A.DataAceite, 103) AS DATA_ACEITE
              , CONVERT(varchar(10), I.Vencimento, 103) AS DATA_ADESÃO
              , IIF(M.datprom <> NULL, '', CONVERT(varchar(10), (SELECT TOP 1 datprom FROM Mensalidade WHERE Inscricao  = A.Inscricao AND datprom IS NOT NULL ORDER BY datprom DESC), 103)) AS DATA_IMPRESSAO
              , IIF (CE.DataSaida IS NULL, 'N/A', CONVERT(varchar(10), 
                (
                  select top 1 DataSaida 
                  from ControleEntrega 
                  left join ControleEntregaItens
                  on ControleEntregaItens.Codigo = ControleEntrega.Codigo
                  where ControleEntregaItens.Inscricao = A.Inscricao order by DataSaida desc), 103)
                )	AS DATA_AGENDAMENTO
              FROM Associados AS A
              INNER JOIN STATUS AS S
              ON A.STATUS = S.CODIGO
              LEFT JOIN orderRequest AS O
              ON O.inscricao = A.Inscricao
              LEFT JOIN Inscricao AS I
              ON I.Inscricao = A.Inscricao
              LEFT JOIN Mensalidade AS M
              ON M.Inscricao = A.Inscricao
              LEFT JOIN ControleEntregaItens AS CEI
              ON CEI.Inscricao = A.Inscricao
              LEFT JOIN ControleEntrega AS CE
              ON CE.Codigo = CEI.Codigo
              WHERE (A.Data BETWEEN '$datainicio 00:00:00.000' AND '$datafim 23:59:59.999')
              --AND I.Vencimento < '2020-09-01'
              --AND A.Status = 1
              and A.SubGrupo IN  (
                                'ZE',
                                'ZT',
                                'ZS',
                                'ZSC',
                                'ZT65',
                                'ZTC',
                                'ZF',
                                'ZTC65',
                                'ZFC',
                                'ZEC',
                                'ZP',
                                'ZSUC',
                                'ZP4',
                                'ZPA',
                                'ZPET',
                                'ITAPA'
                              )
              --(A.Inscricao LIKE '880%' OR A.Inscricao LIKE '2018%' OR A.Inscricao LIKE '2019%') AND LEN(A.Inscricao) = 8
              --AND Grupo IN ('SITE')
              --AND A.Vendedor IN ('UST', 'EC0')
              --AND A.Grupo IN ('ZP')
              ORDER BY A.INSCRICAO DESC
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
            <h3 class="card-title">Relatório Pós Venda</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
                <tr>
                  <th>CONTRATO</th>
                  <th title ="STATUS ATENDIMENTO">STATUS</th>
                  <th title ="TIPO DE COBRANÇA">COB.</th>
                  <th title ="RECORRENTE">REC.</th>
                  <th>ACEITE</th>
                  <th>DATA ACEITE</th>
                  <th>GRUPO</th>
                  <th>SUB GRUPO</th>
                  <th>TIPO VENDA</th>
                  <th title ="VENDEDOR">VEND.</th>
                  <th>ORIGEM VENDA</th>
                  <th>DATA VENDA</th>
                  <th>DATA ADESÃO</th>
                  <th>BOLETO IMPRESSO</th>
                  <th title ="DATA AGENDAMENTO">DATA AGENDA</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['INSCRICAO']; ?></td>
            <td><?php echo strtoupper($r['STATUS']); ?></td>
						<td><?php echo strtoupper($r['TIPO_COBRANCA']); ?></td>
						<td><?php echo strtoupper($r['RECORRENTE']); ?></td>
						<td><?php echo $r['ACEITE']; ?></td>
						<td><?php echo $r['DATA_ACEITE']; ?></td>
						<td><?php echo $r['GRUPO']; ?></td>
						<td><?php echo strtoupper($r['SUBGRUPO']); ?></td>
						<td><?php echo strtoupper($r['TIPOVENDA']); ?></td>
						<td><?php echo strtoupper($r['VENDEDOR']); ?></td>
						<td><?php echo strtoupper($r['ORIGEM_VENDA']); ?></td>
						<td><?php echo strtoupper($r['DATA_VENDA']); ?></td>
						<td><?php echo strtoupper($r['DATA_ADESÃO']); ?></td>
						<td><?php echo strtoupper($r['DATA_IMPRESSAO']); ?></td>
						<td><?php echo strtoupper($r['DATA_AGENDAMENTO']); ?></td>
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