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
$planilha = '#';

$banco  = $_POST ["banco"];
$campo = $_POST ["campo"];
$busca = $_POST ["busca"];
$referencia = $_POST ["referencia"];

if ($campo == 1){
  $res_query = "
                select top 1000 BoletoTitulo.BoletoContaCodigo AS CONTA
                , BancoCodigo AS CODIGO_BANK
                , CONCAT(BancoAgencia, '-', BancoCodigoDv) AS BANCO_AG
                , CONCAT(BancoConta, '-', BancoContaDv) AS BANCO_CONTA
                , BancoCarteiraCodigo AS CARTEIRA
                , CONVERT(VARCHAR(10), TituloVencimento, 103) AS VENCIMENTO
                , TituloNumeroDocumento AS REFERENCIA
                , TituloNossoNumero AS NOSSO_NUMERO
                , TituloSacado AS CLIENTE
                , TituloInscricao AS INSCRICAO
                , TituloValorDocumento AS VALOR
                , CONCAT( CONVERT(VARCHAR(10), BoletoTitulo.DataInsert, 103), ' - ', CONVERT(VARCHAR(8), BoletoTitulo.DataInsert, 108) ) AS CRIADO
                , usuarios.Nick as USUARIO
                , Extrato.ValorPago AS VLR_PAGAMENTO
                , CONVERT(VARCHAR(10), Extrato.Pagamento, 103) AS DATA_PAGAMENTO
                , EXTRATO.Usuario AS USUARIO_BAIXA
                , TituloLinhaDigitavel as LINHA
                , IIF (BoletoTitulo.TituloNossoNumero = vwParcela.NumBoleto, 'NÃO', 'SIM') AS BOLETO_EXCLUIDO
                from BoletoTitulo 
                inner join Usuarios on usuarios.Codigo = BoletoTitulo.Usuinsert
                left join Extrato on (CONCAT (EXTRATO.Inscricao,'-',Extrato.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
                left join vwParcela on (CONCAT (vwParcela.Inscricao,'-',vwParcela.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
                where TituloNossoNumero LIKE '%$busca'
                ORDER BY BoletoTitulo.DataInsert DESC
  ";
  }
  if ($campo == 2)
  {
  $res_query = "
                select top 1000 BoletoTitulo.BoletoContaCodigo AS CONTA
                , BancoCodigo AS CODIGO_BANK
                , CONCAT(BancoAgencia, '-', BancoCodigoDv) AS BANCO_AG
                , CONCAT(BancoConta, '-', BancoContaDv) AS BANCO_CONTA
                , BancoCarteiraCodigo AS CARTEIRA
                , CONVERT(VARCHAR(10), TituloVencimento, 103) AS VENCIMENTO
                , TituloNumeroDocumento AS REFERENCIA
                , TituloNossoNumero AS NOSSO_NUMERO
                , TituloSacado AS CLIENTE
                , TituloInscricao AS INSCRICAO
                , TituloValorDocumento AS VALOR
                , CONCAT( CONVERT(VARCHAR(10), BoletoTitulo.DataInsert, 103), ' - ', CONVERT(VARCHAR(8), BoletoTitulo.DataInsert, 108) ) AS CRIADO
                , usuarios.Nick as USUARIO
                , Extrato.ValorPago AS VLR_PAGAMENTO
                , CONVERT(VARCHAR(10), Extrato.Pagamento, 103) AS DATA_PAGAMENTO
                , EXTRATO.Usuario AS USUARIO_BAIXA
                , TituloLinhaDigitavel as LINHA
                , IIF (BoletoTitulo.TituloNossoNumero = vwParcela.NumBoleto, 'NÃO', 'SIM') AS BOLETO_EXCLUIDO
                from BoletoTitulo 
                inner join Usuarios on usuarios.Codigo = BoletoTitulo.Usuinsert
                left join Extrato on (CONCAT (EXTRATO.Inscricao,'-',Extrato.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
                left join vwParcela on (CONCAT (vwParcela.Inscricao,'-',vwParcela.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
                where dbo.TiraLetras(TituloLinhaDigitavel)  = '$busca'
              ORDER BY BoletoTitulo.DataInsert DESC
  ";
  }
  if ($campo == 3)
  {
  $res_query = "
                select top 1000 BoletoTitulo.BoletoContaCodigo AS CONTA
                , BancoCodigo AS CODIGO_BANK
                , CONCAT(BancoAgencia, '-', BancoCodigoDv) AS BANCO_AG
                , CONCAT(BancoConta, '-', BancoContaDv) AS BANCO_CONTA
                , BancoCarteiraCodigo AS CARTEIRA
                , CONVERT(VARCHAR(10), TituloVencimento, 103) AS VENCIMENTO
                , TituloNumeroDocumento AS REFERENCIA
                , TituloNossoNumero AS NOSSO_NUMERO
                , TituloSacado AS CLIENTE
                , TituloInscricao AS INSCRICAO
                , TituloValorDocumento AS VALOR
                , CONCAT( CONVERT(VARCHAR(10), BoletoTitulo.DataInsert, 103), ' - ', CONVERT(VARCHAR(8), BoletoTitulo.DataInsert, 108) ) AS CRIADO
                , usuarios.Nick as USUARIO
                , Extrato.ValorPago AS VLR_PAGAMENTO
                , CONVERT(VARCHAR(10), Extrato.Pagamento, 103) AS DATA_PAGAMENTO
                , EXTRATO.Usuario AS USUARIO_BAIXA
                , TituloLinhaDigitavel as LINHA
                , IIF (BoletoTitulo.TituloNossoNumero = vwParcela.NumBoleto, 'NÃO', 'SIM') AS BOLETO_EXCLUIDO
                from BoletoTitulo 
                inner join Usuarios on usuarios.Codigo = BoletoTitulo.Usuinsert
                left join Extrato on (CONCAT (EXTRATO.Inscricao,'-',Extrato.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
                left join vwParcela on (CONCAT (vwParcela.Inscricao,'-',vwParcela.Referencia)) = (CONCAT(BoletoTitulo.TituloInscricao, '-', TituloNumeroDocumento))
                where TituloInscricao = '$busca' AND TituloNumeroDocumento LIKE '$referencia'
                ORDER BY BoletoTitulo.DataInsert DESC
  ";
  }
  
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
            <h3 class="card-title">Consulta Boletos</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
                <tr>
                  <th>CART.</th>
                  <th>INSCRICAO</th>
                  <th>CLIENTE</th>
                  <th>COD. CONTA</th>
                  <th>BANCO</th>
                  <th>AG.</th>
                  <th>CONTA</th>
                  <th>REF.</th>
                  <th>NOSSO NUMERO</th>
                  <th title="LINHA DIGITAVEL" style="width: 3%" class="text-center">LINHA DIG.</th>
                  <th>VENCIMENTO</th>
                  <th>VALOR TITULO</th>
                  <th>DATA PGTO</th>
                  <th>VALOR PGTO</th>
                  <th>BAIXA POR:</th>
                  <th>CRIADO EM:</th>
                  <th>CRIADO POR:</th>
                  <th>EXCLUI</th>
                  <th>LOG</th>
                </tr>
                </thead>
                <tbody>
                <?php
							   foreach($resultado as $r) {
							?>
								<tr>
                  <td><?php echo strtoupper($r['CARTEIRA']); ?></td>
                  <td><?php echo strtoupper($r['INSCRICAO']); ?></td>
                  <td><?php echo strtoupper($r['CLIENTE']); ?></td>
                  <td><?php echo strtoupper($r['CONTA']); ?></td>
                  <td><?php echo strtoupper($r['CODIGO_BANK']); ?></td>
                  <td><?php echo strtoupper($r['BANCO_AG']); ?></td>
                  <td><?php echo strtoupper($r['BANCO_CONTA']); ?></td>
                  <td><?php echo strtoupper($r['REFERENCIA']); ?></td>
                  <td><?php echo strtoupper($r['NOSSO_NUMERO']); ?></td>
                  <td style="width: 3%" class="text-center"><input size = "5" type="text" id="linha" name="linha" value="<?php echo strtoupper($r['LINHA']); ?>">
                  <button title="Copiar Linha Digitável" class="btn btn-info" onClick="copiarTexto()"><i class="fas fa-copy"></button></td>
                  <td><?php echo strtoupper($r['VENCIMENTO']); ?></td>
                  <td><?php echo number_format ($r['VALOR'], 2,',','.'); ?></td>
                  <td><?php echo strtoupper($r['DATA_PAGAMENTO']); ?></td>
                  <td><?php echo number_format ($r['VLR_PAGAMENTO'], 2,',','.'); ?></td>
                  <td><?php echo strtoupper($r['USUARIO_BAIXA']); ?></td>
                  <td><?php echo strtoupper($r['CRIADO']); ?></td>
                  <td><?php echo strtoupper($r['USUARIO']); ?></td>
                  <td><?php echo strtoupper($r['BOLETO_EXCLUIDO']); ?></td>
                  <td><a class="btn btn-danger" href="javascript:abrir('/relatorios/log.php?numero=<?php echo strtoupper($r['NOSSO_NUMERO']);?>');"><i class="fas fa-eye"></i></a></td>
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
<script>
      function abrir(URL) {
    window.open(URL, 'janela', 'width=1080, height=720, top=100, left=699, scrollbars=yes, status=no, toolbar=no, location=no, menubar=no, resizable=no, fullscreen=no')
}
 </script>
 <script>
  function copiarTexto() {
    var textoCopiado = document.getElementById("linha");
    textoCopiado.select();
    document.execCommand("Copy");
    alert("Copiado: " + textoCopiado.value);

    //document.execCommand("Copy");
    //alert("Copiado: " + linha);
  }
</script>
</body>
</html>

