<?php
require_once "conn\conexao_mssql_zelo.php";
function converteData($data){
    if(count(explode("/",$data)) > 1): 
         return implode("-",array_reverse(explode("/",$data)));
    elseif(count(explode("-",$data)) > 1): 
         return implode("/",array_reverse(explode("-",$data)));
    endif;
}
//$datainicio  = converteData($_POST ["datainicio3"]);
//$datafim  = converteData($_POST ["datafim3"]);
 
try{
 
    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("select
	  Inscricao Matricula
	, Nome NomeVida
	, '' NomeTitular
	, 'TITULAR' TipoVida
	, CONVERT(VARCHAR(10), Data, 103) AS DataAdmissao
	, CONVERT(VARCHAR(10)
	, Nascimento, 103) AS DataNascimento
	, Sexo
	, CPF
	, '0,00' Limite
	, Cpf Cartao
	, 'ATIVO' SituacaoCartao
	, 'ATIVO' SituacaoVida
	, 'COMPANHIA  BRASILEIRA DE BENEFÍCIOS E INTELIGENCIA' PlanoCobertura 
	from associados
	where status in (1, 2)");
    $result   = $query->fetchAll();
 
 }catch(Exception $e){
 
    echo $e->getMessage();
    exit;

 }
    //declaramos uma variavel para monstarmos a tabela
    $dadosXls  = "";
    $dadosXls .= "  <table>";
	$dadosXls .= " 	    <tr>";
    $dadosXls .= "          <th>Matricula</th>";
    $dadosXls .= "          <th>NomeVida</th>";
    $dadosXls .= "          <th>NomeTitular</th>";
    $dadosXls .= "          <th>DataAdmissao</th>";	
    $dadosXls .= "          <th>DataNascimento</th>";
    $dadosXls .= "          <th>Sexo</th>";
    $dadosXls .= "          <th>CPF</th>";
	$dadosXls .= "          <th>SituacaoCartao</th>";
	$dadosXls .= "          <th>SituacaoVida</th>";
	$dadosXls .= "          <th>PlanoCobertura</th>";
    $dadosXls .= "      </tr>";
    //incluimos nossa conexão
    //include_once('Conexao.class.php');
    //instanciamos
    //$pdo = new Conexao();
    //mandamos nossa query para nosso método dentro de conexao dando um return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //$result = $pdo->select("SELECT id,nome,email FROM cadastro");
    //varremos o array com o foreach para pegar os dados
    foreach($result as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".$res['Matricula']."</td>";
        $dadosXls .= "          <td>".$res['NomeVida']."</td>";
        $dadosXls .= "          <td>".$res['NomeTitular']."</td>";
        $dadosXls .= "          <td>".$res['DataAdmissao']."</td>";
        $dadosXls .= "          <td>".$res['DataNascimento']."</td>";
        $dadosXls .= "          <td>".$res['Sexo']."</td>";
        $dadosXls .= "          <td>".$res['CPF']."</td>";
		$dadosXls .= "          <td>".$res['SituacaoCartao']."</td>";
		$dadosXls .= "          <td>".$res['SituacaoVida']."</td>";
		$dadosXls .= "          <td>".$res['PlanoCobertura']."</td>";
        $dadosXls .= "      </tr>";
    }
/*
                <td><?php echo number_format ($res['VALORPAGO'], 2,',','.'); ?></td>
*/
	
    $dadosXls .= "  </table>";
 
    // Definimos o nome do arquivo que será exportado  
    $arquivo = "Clientes_ativos.xls";  
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