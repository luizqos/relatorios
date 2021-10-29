<?php
//require_once "conn\conexao_mssql_zelo.php";
function converteData($data){
    if(count(explode("/",$data)) > 1): 
         return implode("-",array_reverse(explode("/",$data)));
    elseif(count(explode("-",$data)) > 1): 
         return implode("/",array_reverse(explode("-",$data)));
    endif;

$planilha = "#";

$res_query = "
select top 10
    Inscricao Matricula
  , Nome NomeVida
  , '' NomeTitular
  , 'TITULAR' TipoVida
  , CONVERT(VARCHAR(10), Data, 103) AS DataAdmissao
  , CONVERT(VARCHAR(10), Nascimento, 103) AS DataNascimento
  , Sexo
  , Cpf
  , '0,00' Limite
  , Cpf Cartao
  , 'ATIVO' SituacaoCartao
  , 'ATIVO' SituacaoVida
  , 'COMPANHIA  BRASILEIRA DE BENEFÍCIOS E INTELIGENCIA' PlanoCobertura 
  from associados
  where status in (1, 2) and Grupo not in ('PRE')
UNION ALL
  select top 10 Dependentes.Inscricao Matricula
  , Dependentes.Nome NomeVida
  , Associados.Nome NomeTitular
  , 'DEPENDENTE' TipoVida
  , CONVERT(VARCHAR(10), Associados.Data, 103) AS DataAdmissao
  , CONVERT(VARCHAR(10), Dependentes.Nascimento, 103) AS DataNascimento
  , Dependentes.DepSexo AS Sexo
  ,Dependentes.Cpf
  , '0,00' Limite
  , Dependentes.Cpf Cartao
  , 'ATIVO' SituacaoCartao
  , 'ATIVO' SituacaoVida
  , 'COMPANHIA  BRASILEIRA DE BENEFÍCIOS E INTELIGENCIA' PlanoCobertura 
  from Dependentes 
  INNER JOIN ASSOCIADOS ON Associados.Inscricao = Dependentes.Inscricao
  where Dependentes.Inscricao in(select Inscricao from associados where status in (1, 2) AND Grupo not in ('PRE')) 
  and LEN(dependentes.CPF) BETWEEN 8 and 11
  and Dependentes.CPF not like ('%000000%')
";
require_once "conn/conexao_mssql_zelo.php";


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
            <h3 class="card-title">Ativos</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
				<tr>
					<th>Matricula</th>
					<th>NomeVida</th>
					<th>NomeTitular</th>
					<th>TipoVida</th>
					<th>DataAdmissao</th>
					<th>DataNascimento</th>
          <th>Sexo</th>
          <th>Cpf</th>
          <th>Limite</th>
          <th>Cartao</th>
          <th>SituacaoCartao</th>
          <th>SituacaoVida</th>
          <th>PlanoCobertura</th>
				</tr>
                </thead>
                <tbody> 
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo $r['Matricula']; ?></td>
						<td><?php echo $r['NomeVida']; ?></td>
						<td><?php echo $r['NomeTitular']; ?></td>
						<td><?php echo $r['TipoVida']; ?></td>
						<td><?php echo $r['DataAdmissao']; ?></td>
            <td><?php echo $r['DataNascimento']; ?></td>
            <td><?php echo $r['Sexo']; ?></td>
            <td><?php echo $r['Cpf']; ?></td>
            <td><?php echo $r['Limite']; ?></td>
            <td><?php echo $r['Cartao']; ?></td>
            <td><?php echo $r['SituacaoCartao']; ?></td>
            <td><?php echo $r['SituacaoVida']; ?></td>
            <td><?php echo $r['PlanoCobertura']; ?></td>
					</tr>

          <?php
            $servidor = "localhost";
            $usuario  = "root";
            $senha = "";
            $bd = "relatorios";
            $Matricula = $r['Matricula'];
            $NomeVida = $r['NomeVida'];
            $NomeTitular = $r['NomeTitular'];
            $TipoVida = $r['TipoVida'];
            $DataAdmissao = $r['DataAdmissao'];
            $DataNascimento = $r['DataNascimento'];
            $Sexo = $r['Sexo'];
            $Cpf = $r['Cpf'];
            $Limite = $r['Limite'];
            $Cartao = $r['Cartao'];
            $SituacaoCartao = $r['SituacaoCartao'];
            $SituacaoVida = $r['SituacaoVida'];
            $PlanoCobertura = $r['PlanoCobertura'];

            $conn = mysqli_connect($servidor, $usuario, $senha, $bd);

            //$queryEmpresa = "SELECT idClientes FROM `clientes` where coligada = $coligada and filial = $filial LIMIT 0,1";
            //$res_empresa = mysqli_query($conn,$queryEmpresa);

            //$j = 0;
            //$empresa = 1;
            //while($fetch = mysqli_fetch_row($res_empresa)){
              //for($j = 0;$j < 1;$j++){
                //    $empresa = ($fetch[$j]); 
                //}
            //}

            $query_insert = "INSERT INTO `ativos` ( `Matricula` , `NomeVida` , `NomeTitular` , `TipoVida`, `DataAdmissao` , `DataNascimento` , `Sexo` , `Cpf` , `Limite` , `Cartao` , `SituacaoCartao` , `SituacaoVida` , `PlanoCobertura` ) 
            VALUES ('$Matricula', '$NomeVida', '$NomeTitular', '$TipoVida', '$DataAdmissao', '$DataNascimento', '$Sexo', '$Cpf', '$Limite', '$Cartao', '$SituacaoCartao', '$SituacaoVida', '$PlanoCobertura')";
            mysqli_query($conn,$query_insert);
            //mysqli_close($conn);
        ?>

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
