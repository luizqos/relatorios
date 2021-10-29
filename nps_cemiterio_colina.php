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
$cemiterio = $_POST ["cemiterio"];
if ($cemiterio == 1){
$nomecemiterio = 'Colina BH';	
}if ($cemiterio == 2)
{
$nomecemiterio = 'Colina NT';
}
if ($cemiterio == 3)
{
$nomecemiterio = 'Belo Vale';
}
if ($cemiterio == 4)
{
$nomecemiterio = 'Jardim das Paineiras';
}
if ($cemiterio == 5)
{
$nomecemiterio = 'Vertical Guarulhos';
}
$banco  = $_POST ["banco"];
$datainicio  = converteData($_POST ["datainicio"]);
$datafim  = converteData($_POST ["datafim"]);	
$planilha = "gerar-nps_cemiterio_colina.php?datai=$datainicio&dataf=$datafim&cemiterio=$cemiterio";
$res_query = "--TRANSLADO
 SELECT DISTINCT Translados.Requerente AS 'NOME',
                    case
					when dbo.TiraLetras(Translados.Celular) is not null 
					and dbo.TiraLetras(Translados.Celular) <> ' ' 
					and Translados.Celular not like '0%'
					and len(dbo.tiraletras(Translados.Celular)) >= 9  
					and (substring(dbo.tiraletras(Translados.Celular), 3, 1) in('9', '8', '7') 
							OR substring(dbo.tiraletras(Translados.Celular), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(celular), 3,9)
					then dbo.TiraLetras(Translados.Celular)
					when dbo.TiraLetras(Translados.Telefone) is not null 
					and dbo.TiraLetras(Translados.Telefone) <> ' '
					and Translados.Telefone not like '0%'
					and len(dbo.tiraletras(Translados.Telefone)) >= 9 
					and (substring(dbo.tiraletras(Translados.Telefone), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Translados.Telefone), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(telefone), 3,9)
					then dbo.tiraletras(Translados.Telefone)
					when dbo.TiraLetras(Translados.Telefone) is not null 
					and dbo.TiraLetras(Translados.Telefone) <> ' '
					and Translados.Telefone not like '0%'
					and len(dbo.tiraletras(Translados.Telefone)) >= 9 
					and (substring(dbo.tiraletras(Translados.Telefone), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Translados.Telefone), 1, 1) in('9', '8', '7')
						)
					then dbo.TiraLetras(Translados.Telefone)
					--then substring(dbo.TiraLetras(telefone2), 3,9)
				end AS 'CELULAR', ISNULL(Cidades.Nome,'') AS 'CIDADE',  ISNULL(Cidades.UF,'') AS 'UF', Translados.INSCRICAO, Grupos.descricao FUNERARIA,  Associados.GRUPO,'TRANSLADO' SERVIÇO 
FROM Translados 
LEFT JOIN Associados ON Associados.Inscricao = Translados.Inscricao
INNER JOIN grupos ON associados.grupo = grupos.grupo
LEFT JOIN Cidades ON Cidades.Codigo = Translados.Cidade
WHERE Translados.DataTranslados BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.000' 
AND Associados.Status IN (1,2)
AND Associados.Filial = ('$cemiterio')
UNION ALL
--EXUMAÇÃO
 SELECT DISTINCT Exumacao.Requerente AS 'Nome',
                    case
					when dbo.TiraLetras(Exumacao.Celular) is not null 
					and dbo.TiraLetras(Exumacao.Celular) <> ' ' 
					and Exumacao.Celular not like '0%'
					and len(dbo.tiraletras(Exumacao.Celular)) >= 9  
					and (substring(dbo.tiraletras(Exumacao.Celular), 3, 1) in('9', '8', '7') 
							OR substring(dbo.tiraletras(Exumacao.Celular), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(celular), 3,9)
					then dbo.TiraLetras(Exumacao.Celular)
					when dbo.TiraLetras(Exumacao.Telefone) is not null 
					and dbo.TiraLetras(Exumacao.Telefone) <> ' '
					and Exumacao.Telefone not like '0%'
					and len(dbo.tiraletras(Exumacao.Telefone)) >= 9 
					and (substring(dbo.tiraletras(Exumacao.Telefone), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Exumacao.Telefone), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(telefone), 3,9)
					then dbo.tiraletras(Exumacao.Telefone)
					when dbo.TiraLetras(Exumacao.Telefone) is not null 
					and dbo.TiraLetras(Exumacao.Telefone) <> ' '
					and Exumacao.Telefone not like '0%'
					and len(dbo.tiraletras(Exumacao.Telefone)) >= 9 
					and (substring(dbo.tiraletras(Exumacao.Telefone), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Exumacao.Telefone), 1, 1) in('9', '8', '7')
						)
					then dbo.TiraLetras(Exumacao.Telefone)
					--then substring(dbo.TiraLetras(telefone2), 3,9)
				end AS 'Celular', ISNULL(Cidades.Nome,'') AS Cidade,  ISNULL(Cidades.UF,'') AS 'UF', Exumacao.Inscricao, Grupos.descricao FUNERARIA,  Associados.Grupo, 'EXUMAÇÃO' SERVIÇO
FROM Exumacao 
LEFT JOIN Associados ON Associados.Inscricao = Exumacao.Inscricao
INNER JOIN grupos ON associados.grupo = grupos.grupo
LEFT JOIN Cidades ON Cidades.Codigo = Exumacao.Cidade
WHERE Exumacao.DataExumacao BETWEEN '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
AND Associados.Status IN (1,2)
AND Associados.Filial = ('$cemiterio')
UNION ALL
--SEPULTAMENTO
select DISTINCT Obitos.NomeDeclarante AS 'Nome',
					case
					when dbo.TiraLetras(CelularDeclarante) is not null 
					and dbo.TiraLetras(CelularDeclarante) <> ' ' 
					and CelularDeclarante not like '0%'
					and len(dbo.tiraletras(CelularDeclarante)) >= 9  
					and (substring(dbo.tiraletras(CelularDeclarante), 3, 1) in('9', '8', '7') 
							OR substring(dbo.tiraletras(CelularDeclarante), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(celular), 3,9)
					then dbo.TiraLetras(CelularDeclarante)
					when dbo.TiraLetras(TelefoneDeclarante) is not null 
					and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					and TelefoneDeclarante not like '0%'
					and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(telefone), 3,9)
					then dbo.tiraletras(TelefoneDeclarante)
					when dbo.TiraLetras(TelefoneDeclarante) is not null 
					and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					and Telefone not like '0%'
					and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						)
					then dbo.TiraLetras(TelefoneDeclarante)
					--then substring(dbo.TiraLetras(telefone2), 3,9)
				end AS 'Celular',  Cidades.nome CIDADE, Cidades.uf UF, Associados.inscricao INSCRICAO, Grupos.descricao FUNERARIA,  Associados.Grupo, 'SEPULTAMENTO' SERVIÇO 
FROM obitos
LEFT JOIN Associados ON Associados.Inscricao = Obitos.Inscricao
INNER JOIN cidades ON associados.cidade = cidades.codigo
INNER JOIN grupos ON associados.grupo = grupos.grupo
WHERE Sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
AND Associados.Status IN (1,2)
AND Associados.Filial = ('$cemiterio')
UNION ALL
--SEPULTAMENTO NPS
SELECT associados.NOME, 
	CELULAR = case
					when dbo.TiraLetras(Celular) is not null 
					and dbo.TiraLetras(Celular) <> ' ' 
					and Celular not like '0%'
					and len(dbo.tiraletras(Celular)) >= 9  
					and (substring(dbo.tiraletras(Celular), 3, 1) in('9', '8', '7') 
							OR substring(dbo.tiraletras(Celular), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(celular), 3,9)
					then dbo.TiraLetras(Celular)
					when dbo.TiraLetras(Telefone2) is not null 
					and dbo.TiraLetras(Telefone2) <> ' '
					and Telefone2 not like '0%'
					and len(dbo.tiraletras(Telefone2)) >= 9 
					and (substring(dbo.tiraletras(Telefone2), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Telefone2), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(telefone), 3,9)
					then dbo.tiraletras(Telefone2)
					when dbo.TiraLetras(Telefone) is not null 
					and dbo.TiraLetras(Telefone) <> ' '
					and Telefone not like '0%'
					and len(dbo.tiraletras(Telefone)) >= 9 
					and (substring(dbo.tiraletras(Telefone), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(Telefone), 1, 1) in('9', '8', '7')
						)
					then dbo.TiraLetras(Telefone)
					--then substring(dbo.TiraLetras(telefone2), 3,9)
				end, 
Cidades.nome CIDADE, 
Cidades.uf UF, 
Associados.inscricao INSCRICAO, 
Grupos.descricao FUNERARIA,  Associados.Grupo,
'SEPULTAMENTO - NPS' SERVIÇO 
FROM associados
INNER JOIN cidades ON associados.cidade = cidades.codigo
INNER JOIN grupos ON associados.grupo = grupos.grupo
WHERE associados.status in(1,2) and 
associados.Inscricao in (select inscricao from obitos where sepultamento between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000')
and associados.filial = ('$cemiterio')
UNION ALL
--CREMAÇÃO
select DISTINCT Obitos.NomeDeclarante AS 'Nome', 
					case
					when dbo.TiraLetras(CelularDeclarante) is not null 
					and dbo.TiraLetras(CelularDeclarante) <> ' ' 
					and CelularDeclarante not like '0%'
					and len(dbo.tiraletras(CelularDeclarante)) >= 9  
					and (substring(dbo.tiraletras(CelularDeclarante), 3, 1) in('9', '8', '7') 
							OR substring(dbo.tiraletras(CelularDeclarante), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(celular), 3,9)
					then dbo.TiraLetras(CelularDeclarante)
					when dbo.TiraLetras(TelefoneDeclarante) is not null 
					and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					and TelefoneDeclarante not like '0%'
					and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						)
					--then substring(dbo.TiraLetras(telefone), 3,9)
					then dbo.tiraletras(TelefoneDeclarante)
					when dbo.TiraLetras(TelefoneDeclarante) is not null 
					and dbo.TiraLetras(TelefoneDeclarante) <> ' '
					and TelefoneDeclarante not like '0%'
					and len(dbo.tiraletras(TelefoneDeclarante)) >= 9 
					and (substring(dbo.tiraletras(TelefoneDeclarante), 3, 1) in('9', '8', '7')
							OR substring(dbo.tiraletras(TelefoneDeclarante), 1, 1) in('9', '8', '7')
						)
					then dbo.TiraLetras(TelefoneDeclarante)
					--then substring(dbo.TiraLetras(telefone2), 3,9)
				end AS 'Celular', Cidades.nome CIDADE, Cidades.uf UF, Associados.inscricao INSCRICAO, Grupos.descricao FUNERARIA,  Associados.Grupo, 'CREMAÇÃO' SERVIÇO 
FROM obitos
LEFT JOIN Associados ON Associados.Inscricao = Obitos.Inscricao
INNER JOIN cidades ON associados.cidade = cidades.codigo
INNER JOIN grupos ON associados.grupo = grupos.grupo
WHERE Obitos.Datacremacao between '$datainicio 00:00:00.000' and '$datafim 23:59:59.000'
AND Associados.Status IN (1,2)
AND Associados.Filial = ('$cemiterio')";
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
            <h3 class="card-title"><b>NPS Cemitérios - <?php echo $nomecemiterio; ?></b> | <b>Período: </b><?php echo $_POST ["datainicio"]; ?> a <?php echo $_POST ["datafim"]; ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table id="example1" class="table table-bordered table-striped text-nowrap">
                <thead>
                <tr>
					<th>NOME</th>
					<th>CELULAR</th>
					<th>INSCRICAO</th>
					<th>NOME</th>
					<th>CIDADE</th>
					<th>SERVIÇO</th>
                </tr>
                </thead>
                <tbody>
                <?php
					foreach($resultado as $r) {
				?>
					<tr>
						<td><?php echo strtoupper($r['NOME']); ?></td>
						<td><?php echo $r['CELULAR']; ?></td>
						<td><?php echo $r['INSCRICAO']; ?></td>
						<td><?php echo strtoupper($r['NOME']); ?></td>
						<td><?php echo strtoupper($r['CIDADE']); ?></td>
						<td><?php echo strtoupper($r['SERVIÇO']); ?></td>
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

