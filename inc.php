<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CCM - CORREÇÃO DE CADASTRO DE MEDICOS</title>
</head>

<body>
<?php

// Data para inserção no Select
date_default_timezone_set('America/Sao_Paulo');
// RECEBENDO OS DADOS PREENCHIDOS DO FORMULÁRIO !


//conectando com o localhost - mysql
$servidor = "localhost";
$usuario  = "root";
$senha = "";
$bd = "relatorios";

$conexao = mysqli_connect($servidor, $usuario, $senha, $bd);

//Query

$query = "INSERT INTO `consulta` (`descricao`, `target`, `query`, `status`) VALUES
('VENDAS (ADESÃO + ACEITE)', 'VENDAS-ADESAO-ACEITE', 'SELECT A.Inscricao\r\n    , A.Grupo\r\n    , A.SubGrupo\r\n    , A.TipoVenda\r\n    , A.Nome\r\n    , I.Valor\r\n    , CONVERT(VARCHAR(10), I.Vencimento, 103) AS Vencimento\r\n    , CONVERT(VARCHAR(10), I.Pagamento, 103) AS Pagamento\r\n    , TC.TipCobDescricao AS Cobranca\r\n	, A.Aceite\r\n	, A.TipoAceite\r\n	, CONVERT(VARCHAR(10), A.DataAceite, 103) AS DataAceite\r\n    FROM associados AS A\r\n    INNER JOIN Inscricao AS I ON I.Inscricao = A.Inscricao\r\n    LEFT JOIN TipoCobranca AS TC ON TC.TipCobCodigo = A.AssTipoCobranca\r\n    WHERE A.Grupo IN (\'PRE\',\'ZELO\')\r\n	AND A.Aceite = \'S\'\r\nAND A.SubGrupo NOT IN (\'ZP\', \'ZPE\') \r\n    AND I.Pagamento IS NOT NULL \r\n    AND (I.Vencimento BETWEEN \'2020-06-01 00:00:00.000\' AND \'2020-06-15 23:59:59.999\')', 1),
('VENDAS PRE - P/ ADESÃO', 'RELATORIO-VENDAS-PRE-ADESAO', 'SELECT A.Inscricao\r\n    , A.Grupo\r\n    , A.SubGrupo\r\n    , A.TipoVenda\r\n    , A.Nome\r\n    , I.Valor\r\n    , CONVERT(VARCHAR(10), I.Vencimento, 103) AS Vencimento\r\n    , CONVERT(VARCHAR(10), I.Pagamento, 103) AS Pagamento\r\n    , TC.TipCobDescricao AS Cobranca\r\n	, A.Aceite\r\n	, A.TipoAceite\r\n	, CONVERT(VARCHAR(10), A.DataAceite, 103) AS DataAceite\r\n    FROM associados AS A\r\n    INNER JOIN Inscricao AS I ON I.Inscricao = A.Inscricao\r\n    LEFT JOIN TipoCobranca AS TC ON TC.TipCobCodigo = A.AssTipoCobranca\r\n    WHERE A.Grupo IN (\'PRE\',\'ZELO\')\r\n	AND A.Aceite = \'S\' \r\n	AND A.SubGrupo NOT IN (\'ZP\', \'ZPE\')\r\n    AND (I.Vencimento BETWEEN \'2020-06-01 00:00:00.000\' AND \'2020-06-15 23:59:59.999\')', 1);";

mysqli_query($conexao,$query);

mysqli_close($conexao);

?> 
</body>
</html>
