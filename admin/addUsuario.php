<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Relatorios</title>
  <link rel="icon" type="image/png" href="../../relatorios/admin/dist/img/favicon.png"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php
include('navbar.php');
include('sidebar.php');
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">Usuários</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
            <h3 class="card-title"><i class="fa fa-users"></i> Usuários</h3>
            </div>
            <div class="card-body">
            <form method="post" name="postForm" accept-charset="utf-8">
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" style="width: 30%"></input>
              </div>
              <div class="form-group">
                <label for="login">Login</label>
                <input id="login" name="login"  class="form-control" style="width: 30%"></input>
              </div>
              <div class="form-group">
                <label for="senha">Senha</label>
                <input id="senha" name="senha" type="password" class="form-control" style="width: 30%"></input>
              </div>
              <div class="form-group" style="width: 30%">
                <label for="tipo">Tipo</label>
                <select class="form-control custom-select" id="tipo" name="tipo" >
                  <option selected disabled>Selecione...</option>
                  <option value="A">Administrador</option>
                  <option value="U">Usuário</option>
                </select>
              </div>
              <div class="form-group" style="width: 30%">
                <label for="status">Status</label>
                <select class="form-control custom-select" id="status" name="status" >
                  <option selected disabled>Selecione...</option>
                  <option value="1">Ativo</option>
                  <option value="0">Inativo</option>
                </select>
              </div>
              <div class="row">
                <div class="col-2">
                  <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
                  <input class="btn btn-primary float-right" type="submit" value="Salvar" />
                </div>
              </div>
              </form>
            </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>    
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
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script>
$(document).ready(function() {
    $('form').submit(function(event) { //Trigger on form submit
        $('#nome + .throw_error').empty();
        $('#login + .throw_error').empty();
        $('#senha + .throw_error').empty();
        $('#tipo + .throw_error').empty();
        $('#status + .throw_error').empty(); //Clear the messages first
        $('#success').empty();

        //Validate fields if required using jQuery

        var postForm = { //Fetch form data
            'nome'     : $('input[name=nome]').val(),
            'login'     : $('input[name=login]').val(),
            'senha'     : $('input[name=senha]').val(),
            'tipo'     : $('select[name=tipo]').val(),
            'status'     : $('select[name=status]').val()//Store name fields value
        };

        $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'insertUsuario.php', //Your form processing file URL
            data      : postForm, //Forms name
            dataType  : 'json',
            success   : function(data) {
                            if (!data.success) { //If fails
                                if (data.errors.nome) { //Returned if any error from process.php
                                    $('.throw_error').fadeIn(1000).html(data.errors.nome); //Throw relevant error
                                }
                            }
                            else {
                                    $('#success').fadeIn(1000).append('<p>' + data.posted + '</p>'); //If successful, than throw a success message
                                }
                            }
        });
        event.preventDefault();
        window.location.href = "usuarios.php"; //Prevent the default submit
    });
});
</script>
</body>
</html>
