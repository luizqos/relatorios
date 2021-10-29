 <?php
// Verificador de sessão 
require "verifica.php"; 
 
// Conexão com o banco de dados 
require "comum.php"; 

require_once "conn/conexao_mssql_zelo.php";

function converteData($data){
    if(count(explode("/",$data)) > 1): 
         return implode("-",array_reverse(explode("/",$data)));
    elseif(count(explode("-",$data)) > 1): 
         return implode("/",array_reverse(explode("-",$data)));
    endif;
  }
 
  $dia  = $_GET['dia'];
  $Pinicio  = $_GET['Pinicio'];
  $datainicial = '01' . '/' . $Pinicio;
  $datainicial  = converteData($datainicial);
  $Pfim  = $_GET['Pfim'];
  $datafinal = $dia . '/' . $Pfim;
  $datafinal  = converteData($datafinal);
  
  $datainicial = new DateTime("$datainicial");
  $datafinal = new DateTime("$datafinal");
  $interval = $datainicial->diff($datafinal);
  
  $meses = $interval->m;
  $meses = $meses + 1; 

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
  
try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("select COUNT(DISTINCT vwParcela.Inscricao) AS QTDE_CONTRATOS,COUNT(vwParcela.Inscricao) AS QTDE_RECEBIMENTOS, Filiais.Empresa, MONTH(Pagamento) Mes, SUM(valorpago) Valor from vwParcela
    inner join Associados on Associados.Inscricao = vwparcela.inscricao
    left outer join Filiais on Filiais.Codigo = Associados.Filial
    where associados.grupo = 'pvida'
    and $periodo
    Group By Filiais.Empresa, MONTH(Pagamento)
    Order by Filiais.Empresa, MONTH(Pagamento)");
    $result   = $query->fetchAll();
 
 }catch(Exception $e){
 
    echo $e->getMessage();
    exit;

 }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
		<meta charset="utf-8">
		<title>Gerenciador de Relatórios | Grupo Zelo</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<head>
	<body>
		<?php
		// Definimos o nome do arquivo que será exportado
    $arquivo = 'RELATORIO DE RECEBIMENTOS DIA_'.$dia.'.xls';
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>EMPRESA</th>";
    $dadosXls .= "          <th>MES</th>";
    $dadosXls .= "          <th>VALOR</th>";
    $dadosXls .= "          <th>Qtde. Contratos</th>";
    $dadosXls .= "          <th>Qtde. Pgtos</th>";
    $dadosXls .= "      </tr>";

    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['Empresa']."</td>";
        $dadosXls .= "          <td>".$res['Mes']."</td>";
        $dadosXls .= "          <td>".number_format($res['Valor'], 2,',','.')."</td>";
        $dadosXls .= "          <td>".$res['QTDE_CONTRATOS']."</td>";
        $dadosXls .= "          <td>".$res['QTDE_RECEBIMENTOS']."</td>";
        $dadosXls .= "      </tr>";
    }
    $dadosXls .= "  </table>";
		
    // Configurações header para forçar o download  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    // Se for o IE9, isso talvez seja necessário
    header('Cache-Control: max-age=1');
       
    // Envia o conteúdo do arquivo  
    echo $dadosXls;  
    exit;
?>
	</body>
</html>