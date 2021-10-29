<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Gerenciador de Relatórios | Grupo Zelo</title>
    <link rel="icon" type="image/png" href="../../relatorios/admin/dist/img/favicon.png"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">

    <link rel="shortcut icon" href="/img/favicon.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="/relatorios/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="/relatorios/asset/css/select2.min.css">
    <link rel="stylesheet" href="/relatorios/asset/css/flat-ui.css">
    <link rel="stylesheet" href="/relatorios/asset/css/font-awesome.min.css">
    <link rel="stylesheet" href="/relatorios/asset/css/datatables.min.css">
    <link rel="stylesheet" href="/relatorios/asset/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="/relatorios/asset/css/style.css">
    <link rel="stylesheet" href="/relatorios/asset/css/custom.css">
    <link rel="stylesheet" href="/relatorios/asset/css/toastr.min.css" />
	<link href="assets/fontawesome/css/all.css" rel="stylesheet">
    <!-- CLIENTE -->
    <link rel="stylesheet" href="/relatorios/asset/css/cliente.css">

    <!-- JS -->
    <script src="/relatorios/asset/js/jquery.min.js"></script>
    <script src="/relatorios/asset/js/handlebars-v4.0.5.js"></script>
    <script src="/relatorios/asset/js/flat-ui.min.js"></script>
    <script src="/relatorios/asset/js/datatables.min.js"></script>
    <script src="/relatorios/asset/js/bootstrap-datepicker.min.js"></script>
    <script src="/relatorios/asset/js/bootstrap-datepicker.pt-BR.min.js"></script>
    <script src="/relatorios/asset/js/select2.js"></script>
    <script src="/relatorios/asset/js/validator.js"></script>
    <script src="/relatorios/asset/js/bootstrap-filestyle.js"></script>
    <script src="/relatorios/asset/js/bootstrap.min.js"></script>
    <script src="/relatorios/asset/js/bootbox.min.js"></script>
    <script src="/relatorios/asset/js/toastr.min.js"></script>
    <script src="/relatorios/asset/js/spin.min.js"></script>
    <script src="/relatorios/asset/js/app.js"></script>
    <script src="/relatorios/asset/js/default.js"></script>

    <!--[if lt IE 9]><script src="/js/lib/vendor/html5shiv-respond.js"></script><![endif]-->
</head>
<body>
<?php

?>

<?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 
?>
    <header id="header">
        <div class="container header-container">
                <div class="header-left">
                    <a href="/Cards" class="brand">
                        <!-- QUANDO TIVER LOGO, EXIBIR APENAS IMAGEM: -->
                        <img id="logo" src="/relatorios/asset/img/logo.png" alt="Grupo Zelo" />
                        <!--<span>Nome do Cliente sem logo</span> -->
                    </a>
                </div>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-container-nav" aria-expanded="false"></button>
                <div class="header-right collapse navbar-collapse" id="header-container-nav">
                <?php 
                      if ($_SESSION["permissao"] == 'A')
                          {
                            Echo '<a href="./admin/index.php" class="btn header-btn-usuario" title = "Home">';
                            Echo '<i class="fas fa fa-home"></i>';
                            Echo ' </a>';
                          }
                ?>
                    <a href="#" class="btn header-btn-usuario" disabled>
                        <i class="fa fa-user"></i>
                        <span><?php echo $_SESSION["nome_usuario"];?></span>
                    </a>
                    <a href="sair.php" class="btn header-btn-usuario" title = "Sair">
                        <i class="fas fa-sign-out-alt"></i>

                    </a>					
                </div>
        </div>
    </header>
    <main id="main">
        <div id="spinner" style="display:none;"></div>
        <div class="container">
            
<!--
<div id="divSolicitacoes" class="pull-right">
    <a class="btn btn-primary" href="/Solicitacao">
        <span class="fa fa-flag-o"></span> Solicitações
    </a>
</div> -->

<div id="cardsPorCategoria"></div>
<div class="container">
<div class="row">

<?php
include "conn/conexao_mysql.php";
$user_id = $_SESSION["id_usuario"];
	$query = "SELECT c.descricao, c.target 
	FROM usuario_relatorios AS UR
	INNER JOIN consulta AS C
	ON C.id = UR.idConsulta
	WHERE UR.idUsuario = $user_id AND C.status = 1
	ORDER BY c.descricao ASC";
	$result = mysqli_query($conexao,$query);
	$j = 0;
	
		while($fetch = mysqli_fetch_row($result)){
			for($j = 0;$j < 1;$j++){
				echo"<div class='col-auto'>";
						echo"<div class='boxes'>";
							echo"<div class='box'>";
								echo"<div class='box-content'>";
									echo"<a data-toggle='modal' data-target='#$fetch[1]' data-trigger='item-modal' class='box-titulo'>$fetch[0]</a>";
									echo"<div class='actions'>";
										echo"<a data-toggle='modal' data-target='#$fetch[1]' data-trigger='item-modal' data-target='#$fetch[1]'><i class='fa fa-plus'></i></a>";
									echo"</div>";
								echo"</div>";
							echo"</div>";
						echo"</div>";
				echo"</div>";		
				
			}

}
mysqli_close($conexao);
?>
</div>
</div>
	</script>
    <script>
      $(document).ready(function () {
        $('.formdata').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR"
        });
      });
    </script>

<form id="pesquisa" name="pesquisa" method="post" action="nps_servicos_funerarios.php">
<!-- Modal Serviços Funerarios-->
<div id="NPS-SERVICO-FUNERARIO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Serviços Funerarios</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="nps_vendas.php">
<!-- Modal Vendas-->
<div id="NPS-EQUIPE-VENDAS" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Equipe de Vendas</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="nps_clientes_ativos.php">
<!-- Modal clientes atios-->
<div id="NPS-CLIENTES-ATIVOS" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Clientes Ativos</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="nps_cemiterio_colina.php">
<!-- Cemiterio Colina-->
<div id="NPS-CEMITERIO-COLINA" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Nps Cemiterio Colina</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_cemiterio.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
		  <td>Cemiterio:</td>
			<td><select class="" name="cemiterio" id="cemiterio">
				  <option value="1">Colina - BH</option>
				  <option value="2">Colina - NITEROI</option>
          <option value="3">BELO VALE - SANTA LUZIA</option>
          <option value="4">PARQUE DAS PAINEIRAS - ARAGUAINA</option>
          <option value="5">VERTICAL - GUARULHOS</option>
				</select></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="nps_cemiterio_belovale.php">
<!-- Modal Cemiterio Belo Vale-->
<div id="NPS-CEMITERIO-BELOVALE" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Nps Cemiterio Belo Vale</h4>
      </div>
      <div class="modal-body">
		  <input type="hidden" id="banco" name="banco" value="conexao_mssql_belovale.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-aceite.php">
<!-- Modal Between Data do exame-->
<div id="RELATORIO-ACEITE" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Relatório de Aceite</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas-sem-aceite.php">
<!-- Relatorio de vendas sem aceite -->
<div id="VENDAS-SEM-ACEITE" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas sem Aceite</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>
<form id="pesquisa" name="pesquisa" method="post" action="utilizacao-urnas.php">
<!-- Relatorio utilização de urna -->
<div id="RELAT-URNAS-UTILIZADAS" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Utilização de Urnas</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="vendas-sem-link.php">
<!-- Modal Between Data do exame-->
<div id="VENDAS-SEM-LINK" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas Sem Link</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="zelo">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>
<form id="pesquisa" name="pesquisa" method="post" action="relatorio-recorrencia-ativa.php">
<!-- Modal Between Data do exame-->
<div id="RELAT-RECORRENCIA-ATIVA" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de Recorrencia Ativas</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="portal-antigo.php">
<!-- Modal NÃO IMPORTADOS DO PORTAL-->
<div id="PORTAL-ANTIGO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zeloi.php">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Contratos Portal Antigo</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-recorrencia-ativa-periodo.php">
<!-- Modal Between Data do exame-->
<div id="RELAT-RECORRENCIA-ATIVA-PERIODO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de Recorrencia Ativas - Periodo</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas-pre-periodo.php">
<!-- Vendas Pre-PERIODO-->
<div id="RELATORIO-VENDAS-PRE-PERIODO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>
<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas-adesao-aceite.php">
<!-- Vendas ACEITE + ADESAO -->
<div id="VENDAS-ADESAO-ACEITE" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>
<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas-pre-adesao.php">
<!-- Vendas ACEITE + ADESAO -->
<div id="RELATORIO-VENDAS-PRE-ADESAO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="atualizalink.php">
<!-- Modal Between Data do exame-->
<div id="ATUALIZA-LINK" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ATUALIZA LINK DE RECORRENCIA</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="valida-l2.php">
<!-- Modal Valida Link 2-->
<div id="VALIDA-L2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
		<input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">VALIDA LINK 2</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-contratos-pre.php">
<!-- Contratos Pre-->
<div id="CONTRATOS-PRE" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Contratos</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Contratos:</td></br>
      <textarea placeholder="Contratos Separados por (,)" name="contratos" id="contratos"
        rows="5" cols="30"
        minlength="1"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-parcelas-quitadas.php">
<!-- Modal Relatorio Parcelas Quitadas -->
<div id="RELAT-PARCELAS-QUITADAS" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de Quitação de Parcelas</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-recebimentos-paxvida.php">
<!-- Modal Relatorio RECEBIMENTOS PAXVIDA -->
<div id="RECEBIMENTOS-PAXVIDA" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de Recebimentos</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
    <td>Dia:</td>
      <td><input name="dia" placeholder=""  size="2" type='text' value="<?php
                                                                          date_default_timezone_set('America/Fortaleza');
                                                                          $yesterday = date("d", mktime(0, 0, 0, date("m") , date("d")-1,date("Y")));
                                                                          echo $yesterday;
                                                                        ?>" 
                                                        class="" id='dia' /></td></br></br>
      <td>Período Inicial:</td>
      <td><input name="Pinicio" size="7"  placeholder="mm/aaaa" type='text' class="" id='Pinicio' /></td>
	  <td>Período Final:</td>
      <td><input name="Pfim" size="7"  placeholder="mm/aaaa" type='text' class="" id='Pfim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-reposicao.php">
<!-- Modal Relatorio Reposições -->
<div id="relatorio-reposicao" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
		<input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de Reposições</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-baixas.php">
<!-- Modal Relatorio baixas -->
<div id="relatorio-baixas" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de baixas</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="ativosaraujo.php">
<!-- Modal Between Data do exame-->
<div id="CLIENTES-ATIVOS-ARAUJO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório Clientes Ativos | Araújo - Henrique Rego</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="linkrecorrencia.php">
<!-- Modal Link Recorrencia -->
<div id="link-recorrencia" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Recorrencia Cliente</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Inscrição:</td>
		  <td><input name="inscricao" placeholder="Informe a inscrição" type='text' class="" id='inscricao' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="consulta-boleto.php">
<!-- Modal Boleto -->
<div id="CONSULTA-BOLETO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Boleto</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Busca:</td>
			<td><select class="" name="campo" id="campo">
				  <option value="1">Nosso Numero</option>
				  <option value="2">Linha Digitável</option>
          <option value="3">Inscrição</option>
				</select></td>
		  <td><input name="busca" placeholder="" type='text' class="" id='busca' /></td>
      </div>
      <div>
      <td>Referencia:</td>
      <td><input name="referencia" placeholder="" type='text' class="" id='referencia' /></td></br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-baixa-cartao.php">
<!-- Modal BAIXA CARTAO -->
<div id="CONSULTA-BAIXA-CARTAO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Boleto</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Inscricao:</td>
		  <td><input name="inscricao" placeholder="" type='text' class="" id='inscricao' /></td>
      </div>
      <div>
      <td>Referencia:</td>
      <td><input name="referencia" placeholder="" type='text' class="" id='referencia' /></td></br>
      </div>
      <div>
      </br>
      <td>Cartão:</td>
      <td><input name="autorizacao" placeholder="Autorização" type='text' class="" id='autorizacao' />
      <input name="nsucv" placeholder="NSUCV" type='text' class="" id='nsucv' />
      </td></br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="consulta-boleto-exclusao.php">
<!-- Modal Boleto Excluido -->
<div id="CONSULTA-BOLETO-EXCLUSAO" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Baixa Cartão</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Busca:</td>
			<td><select class="" name="campo" id="campo">
				  <option value="1">Nosso Numero</option>
				  <option value="2">Inscrição</option>
				</select></td>
		  <td><input name="busca" placeholder="" type='text' class="" id='busca' /></td>
      </div>
      <div>
      <td>Referencia:</td>
      <td><input name="referencia" placeholder="" type='text' class="" id='referencia' /></td></br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

 <form id="pesquisa" name="pesquisa" method="post" action="linkrecorrenciaweb.php">
<!-- Modal Link Recorrencia web -->
<div id="link-recorrencia-web" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Recorrencia Cliente</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Inscrição:</td>
		  <td><input name="inscricao" placeholder="Informe a inscrição" type='text' class="" id='inscricao' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>
 
<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas.php">
<!-- Modal Vendas-->
<div id="RELATORIO-VENDAS" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas por Período</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas-pos-vendas.php">
<!-- Modal Vendas-->
<div id="VENDAS-POS-VENDA" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas por Período</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas-prime.php">
<!-- Modal Vendas-->
<div id="VENDAS-PRIME" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas por Período</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas-carencia.php">
<!-- Modal Vendas-->
<div id="RELATORIO-VENDAS-CARENCIA" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas por Período</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="relatorio-vendas_site.php">
<!-- Modal Vendas SITE-->
<div id="VENDAS-SITE" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Vendas por Período</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
      <td>Data Inicial:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
	  <td>Data Final:</td>
      <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="recusaAdyen.php">
<!-- Modal Link Recorrencia web -->
<div id="recusa-adyen" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pesquisa - Pagamento Recusado Adyen</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Inscrição:</td>
		  <td><input name="inscricao" placeholder="Informe a inscrição" type='text' class="" id='inscricao' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>
<form id="pesquisa" name="pesquisa" method="post" action="Logrenovacao-payzen.php">
<!-- Modal Renovação Payzen -->
<div id="renovacao-payzen" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Renovação Payzen</h4>
      </div>
      <div class="modal-body">
	  	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
        <td>Dia do vencimento:</td>
      <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="link-cobranca.php">
<!-- Modal Between Data do exame-->
<div id="RELAT-LINK-COBRANCA" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de Envio de Link Cobrança - Periodo</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="pesquisa" name="pesquisa" method="post" action="devolucao-cadastro.php">
<!-- Modal Between Data do exame-->
<div id="devolucao-cadastro" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Relatório de Contratos Devolvidos - Cadastro</h4>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="banco" name="banco" value="conexao_mssql_zelo.php">
		  <td>Data Inicial:</td>
		  <td><input name="datainicio" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datainicio' /></td>
		  <td>Data Final:</td>
		  <td><input name="datafim" placeholder="dd/mm/aaaa" type='text' class="formdata" id='datafim' /></td>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
		<button type="submit" name="pesquisar" id="pesquisar" class="btn btn-success">Confirmar</button>
      </div>
    </div>
  </div>
</div>
</form>
    </main>

    <footer id="footer">
        <div class="container">
            <div class="copyright">
                Gerenciador de Relatórios
                <small>
                    <span class="year"></span>
                    &copy; Todos os direitos reservados
                </small>
            </div>
        </div>
    </footer>
    
    <script type="text/javascript">
        
        $.post("/Home/BuscarLogo").success(function (result) {
            if (result) {
                $("#logo").prop("src", result);
            }
        });
    </script>


</body>
</html>
