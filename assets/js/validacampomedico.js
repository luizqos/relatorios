function validaCampoMedico()
{
	if(document.medico.medico.value=="")
	{
	alert("Preencha o Nome do M\u00e9dico!");
	return false;
	}
else
	{
	alert("M\u00e9dico cadastrado com Sucesso!");
	return true;
	}	
return true;
}
<!-- Fim do JavaScript que validará os campos obrigatórios! -->