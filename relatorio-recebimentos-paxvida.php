<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

//Verificação de chamada direta
$id_relatorio = 31;
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
$dia  = $_POST ["dia"];	
$Pinicio  = $_POST ["Pinicio"];	
$datainicial = '01' . '/' . $Pinicio;
$datainicial  = converteData($datainicial);
$Pfim  = $_POST ["Pfim"];	
$datafinal = $dia . '/' . $Pfim;
$datafinal  = converteData($datafinal);

$datainicial = new DateTime("$datainicial");
$datafinal = new DateTime("$datafinal");
$interval = $datainicial->diff($datafinal);

$meses = $interval->m;
$meses = $meses + 1;

//$datai = $datainicial->format('d/m/Y');
//$datai = $datainicial->format('d/m/Y');
//echo "$meses";

// Data de ínicio 
//$date = $datafinal;

// Adiciona 2 meses a data
//$dataIm1 = $datainicial->add(new DateInterval('P1M')); 

// Altera a nova data para o último dia do mês
//$lDayOfMonth = $newDate->modify('last day of this month');


//echo $dataIm1->format('Y-m-d'); // 2017-12-31



IF ($meses == 1)
{
  $dataI = $datainicial->format('Y-m-d');
  $dataF = $datafinal->format('Y-m-d');
 $periodo = "(Pagamento between '$dataI 00:00:00.000' and '$dataF 23:59:59.998')";
}

IF ($meses == 2)
{
  $dataI = $datainicial->format('Y-m-d'); //converte data inicial em String
  $dataF = $datafinal->format('Y-m-d');  //converte data final em String
  
  $dataImD = $datainicial->format('Y-m'); // separa dia da data inicial 
  $dataImD = $dataImD . '-' . $dia; // uni mes/ano com dia, para encontrar data fim

  $dataIm1 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm1 = $dataIm1->format('Y-m-d'); //converte data em String

  //echo "$dataIm1";
 $periodo = "(Pagamento between '$dataI 00:00:00.000' and '$dataImD 23:59:59.998'
              OR Pagamento between '$dataIm1 00:00:00.000' and '$dataF 23:59:59.998'
 )";
}

IF ($meses == 3)
{
  $dataI = $datainicial->format('Y-m-d'); //converte data inicial em String
  $dataF = $datafinal->format('Y-m-d');  //converte data final em String
  
  $dataImD = $datainicial->format('Y-m'); // separa dia da data inicial 
  $dataImD = $dataImD . '-' . $dia; // uni mes/ano com dia, para encontrar data fim

  $dataIm1 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm1 = $dataIm1->format('Y-m-d'); //converte data em String

  $dataFm1 = $datafinal->sub(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataFm1 = $dataFm1->format('Y-m-d'); //converte data em String

  $dataIm2 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm2 = $dataIm2->format('Y-m-d'); //converte data em String

 $periodo = "(Pagamento between '$dataI 00:00:00.000' and '$dataImD 23:59:59.998'
              OR Pagamento between '$dataIm1 00:00:00.000' and '$dataFm1 23:59:59.998'
              OR Pagamento between '$dataIm2 00:00:00.000' and '$dataF 23:59:59.998'
 )";
}

IF ($meses == 4)
{
  $dataI = $datainicial->format('Y-m-d'); //converte data inicial em String
  $dataF = $datafinal->format('Y-m-d');  //converte data final em String
  
  $dataImD = $datainicial->format('Y-m'); // separa dia da data inicial 
  $dataImD = $dataImD . '-' . $dia; // uni mes/ano com dia, para encontrar data fim

  $dataIm1 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm1 = $dataIm1->format('Y-m-d'); //converte data em String

  $dataFm1 = $datafinal->sub(new DateInterval('P2M')); //subtrai 2 mês na data inicial
  $dataFm1 = $dataFm1->format('Y-m-d'); //converte data em String

  $dataIm2 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm2 = $dataIm2->format('Y-m-d'); //converte data em String
  
  $dataFm2 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm2 = $dataFm2->format('Y-m-d'); //converte data em String

  $dataIm3 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm3 = $dataIm3->format('Y-m-d'); //converte data em String

 $periodo = "(Pagamento between '$dataI 00:00:00.000' and '$dataImD 23:59:59.998'
              OR Pagamento between '$dataIm1 00:00:00.000' and '$dataFm1 23:59:59.998'
              OR Pagamento between '$dataIm2 00:00:00.000' and '$dataFm2 23:59:59.998'
              OR Pagamento between '$dataIm3 00:00:00.000' and '$dataF 23:59:59.998'
 )";
}

IF ($meses == 5)
{
  $dataI = $datainicial->format('Y-m-d'); //converte data inicial em String
  $dataF = $datafinal->format('Y-m-d');  //converte data final em String
  
  $dataImD = $datainicial->format('Y-m'); // separa dia da data inicial 
  $dataImD = $dataImD . '-' . $dia; // uni mes/ano com dia, para encontrar data fim

  $dataIm1 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm1 = $dataIm1->format('Y-m-d'); //converte data em String

  $dataFm1 = $datafinal->sub(new DateInterval('P3M')); //subtrai 3 mês na data inicial
  $dataFm1 = $dataFm1->format('Y-m-d'); //converte data em String

  $dataIm2 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm2 = $dataIm2->format('Y-m-d'); //converte data em String
  
  $dataFm2 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm2 = $dataFm2->format('Y-m-d'); //converte data em String

  $dataIm3 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm3 = $dataIm3->format('Y-m-d'); //converte data em String

  $dataFm3 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm3 = $dataFm3->format('Y-m-d'); //converte data em String

  $dataIm4 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm4 = $dataIm4->format('Y-m-d'); //converte data em String

 $periodo = "(Pagamento between '$dataI 00:00:00.000' and '$dataImD 23:59:59.998'
              OR Pagamento between '$dataIm1 00:00:00.000' and '$dataFm1 23:59:59.998'
              OR Pagamento between '$dataIm2 00:00:00.000' and '$dataFm2 23:59:59.998'
              OR Pagamento between '$dataIm3 00:00:00.000' and '$dataFm3 23:59:59.998'
              OR Pagamento between '$dataIm4 00:00:00.000' and '$dataF 23:59:59.998'
 )";
}

IF ($meses == 6)
{
  $dataI = $datainicial->format('Y-m-d'); //converte data inicial em String
  $dataF = $datafinal->format('Y-m-d');  //converte data final em String
  
  $dataImD = $datainicial->format('Y-m'); // separa dia da data inicial 
  $dataImD = $dataImD . '-' . $dia; // uni mes/ano com dia, para encontrar data fim

  $dataIm1 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm1 = $dataIm1->format('Y-m-d'); //converte data em String

  $dataFm1 = $datafinal->sub(new DateInterval('P4M')); //subtrai 3 mês na data inicial
  $dataFm1 = $dataFm1->format('Y-m-d'); //converte data em String

  $dataIm2 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm2 = $dataIm2->format('Y-m-d'); //converte data em String
  
  $dataFm2 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm2 = $dataFm2->format('Y-m-d'); //converte data em String

  $dataIm3 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm3 = $dataIm3->format('Y-m-d'); //converte data em String

  $dataFm3 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm3 = $dataFm3->format('Y-m-d'); //converte data em String

  $dataIm4 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm4 = $dataIm4->format('Y-m-d'); //converte data em String

  $dataFm4 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm4 = $dataFm4->format('Y-m-d'); //converte data em String

  $dataIm5 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm5 = $dataIm5->format('Y-m-d'); //converte data em String

 $periodo = "(Pagamento between '$dataI 00:00:00.000' and '$dataImD 23:59:59.998'
              OR Pagamento between '$dataIm1 00:00:00.000' and '$dataFm1 23:59:59.998'
              OR Pagamento between '$dataIm2 00:00:00.000' and '$dataFm2 23:59:59.998'
              OR Pagamento between '$dataIm3 00:00:00.000' and '$dataFm3 23:59:59.998'
              OR Pagamento between '$dataIm4 00:00:00.000' and '$dataFm4 23:59:59.998'
              OR Pagamento between '$dataIm5 00:00:00.000' and '$dataF 23:59:59.998'
 )";
}

IF ($meses == 7)
{
  $dataI = $datainicial->format('Y-m-d'); //converte data inicial em String
  $dataF = $datafinal->format('Y-m-d');  //converte data final em String
  
  $dataImD = $datainicial->format('Y-m'); // separa dia da data inicial 
  $dataImD = $dataImD . '-' . $dia; // uni mes/ano com dia, para encontrar data fim

  $dataIm1 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm1 = $dataIm1->format('Y-m-d'); //converte data em String

  $dataFm1 = $datafinal->sub(new DateInterval('P5M')); //subtrai 3 mês na data inicial
  $dataFm1 = $dataFm1->format('Y-m-d'); //converte data em String

  $dataIm2 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm2 = $dataIm2->format('Y-m-d'); //converte data em String
  
  $dataFm2 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm2 = $dataFm2->format('Y-m-d'); //converte data em String

  $dataIm3 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm3 = $dataIm3->format('Y-m-d'); //converte data em String

  $dataFm3 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm3 = $dataFm3->format('Y-m-d'); //converte data em String

  $dataIm4 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm4 = $dataIm4->format('Y-m-d'); //converte data em String

  $dataFm4 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm4 = $dataFm4->format('Y-m-d'); //converte data em String

  $dataIm5 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm5 = $dataIm5->format('Y-m-d'); //converte data em String

  $dataFm5 = $datafinal->add(new DateInterval('P1M')); //subtrai 1 mês na data inicial
  $dataFm5 = $dataFm5->format('Y-m-d'); //converte data em String

  $dataIm6 = $datainicial->add(new DateInterval('P1M')); //aumenta 1 mês na data inicial
  $dataIm6 = $dataIm6->format('Y-m-d'); //converte data em String

 $periodo = "(Pagamento between '$dataI 00:00:00.000' and '$dataImD 23:59:59.998'
              OR Pagamento between '$dataIm1 00:00:00.000' and '$dataFm1 23:59:59.998'
              OR Pagamento between '$dataIm2 00:00:00.000' and '$dataFm2 23:59:59.998'
              OR Pagamento between '$dataIm3 00:00:00.000' and '$dataFm3 23:59:59.998'
              OR Pagamento between '$dataIm4 00:00:00.000' and '$dataFm4 23:59:59.998'
              OR Pagamento between '$dataIm5 00:00:00.000' and '$dataFm5 23:59:59.998'
              OR Pagamento between '$dataIm6 00:00:00.000' and '$dataF 23:59:59.998'
 )";
}

/*
echo "data 1: ";
echo "$dataI";
echo "</br>";
echo "data 2: ";
echo "$dataImD";
echo "</br>";
echo "data 3: ";
echo "$dataIm1";
echo "</br>";
echo "data 4: ";
echo "$dataFm1";
echo "</br>";
echo "data 5: ";
echo "$dataIm2";
echo "</br>";
echo "data 6: ";
echo "$dataFm2";
echo "</br>";
echo "data 7: ";
echo "$dataIm3";
echo "</br>";
echo "data 8: ";
echo "$dataFm3";
echo "</br>";
echo "data 9: ";
echo "$dataIm4";
echo "</br>";
echo "data 10: ";
echo "$dataFm4";
echo "</br>";
echo "data 11: ";
echo "$dataIm5";
echo "</br>";
echo "data 12: ";
echo "$dataFm5";
echo "</br>";
echo "data 11: ";
echo "$dataIm6";
echo "</br>";
echo "data 12: ";
echo "$dataF";
*/

$planilha = "gerar-recebimentos-paxvida.php";
$planilha = "gerar-recebimentos-paxvida.php?Pinicio=$Pinicio&Pfim=$Pfim&dia=$dia";
$res_query = "select COUNT(DISTINCT vwParcela.Inscricao) AS QTDE_CONTRATOS,COUNT(vwParcela.Inscricao) AS QTDE_RECEBIMENTOS, Filiais.Empresa, MONTH(Pagamento) Mes, SUM(valorpago) Valor from vwParcela
inner join Associados on Associados.Inscricao = vwparcela.inscricao
left outer join Filiais on Filiais.Codigo = Associados.Filial
where associados.grupo = 'pvida'
and $periodo
Group By Filiais.Empresa, MONTH(Pagamento)
Order by Filiais.Empresa, MONTH(Pagamento)";
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
            <h3 class="card-title">Relatório de Recebimentos</h3></br>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
				<tr>
					<th>Empresa</th>
					<th>Mês</th>
					<th>Valor</th>
          <th>Qtde. Contratos</th>
          <th>Qtde. Pgtos</th>
				</tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo strtoupper($r['Empresa']); ?></td>
						<td><?php echo strtoupper($r['Mes']); ?></td>
						<td><?php echo number_format ($r['Valor'], 2,',','.'); ?></td>
            <td><?php echo strtoupper($r['QTDE_CONTRATOS']); ?></td>
            <td><?php echo strtoupper($r['QTDE_RECEBIMENTOS']); ?></td>
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