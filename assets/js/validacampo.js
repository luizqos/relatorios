function validaCampo()
{
if(document.cadastro.unidade.value=="")
	{
	alert("Preencha o campo Unidade!");
	return false;
	}
else
	if(document.cadastro.unidade.value=="0")
	{
	alert("Verifique valor digitado para a unidade!");
	return false;
	}
else
	if(document.cadastro.unidade.value>"41")
	{
	alert("Verifique valor digitado para a unidade!");
	return false;
	}	
else
	if(document.cadastro.atendimento.value=="")
	{
	alert("Preencha o campo Atendimento!");
	return false;
	}
else
		if(document.cadastro.atendimento.value=="0")
	{
	alert("O Atendimento n\u00e3o pode ser 0 (zero)!");
	return false;
	}
else
	if(document.cadastro.paciente.value=="")
	{
	alert("Preencha o campo Paciente!");
	return false;
	}
else
	if(document.cadastro.exame.value=="")
	{
	alert("Preencha o campo Exame!");
	return false;
	}
else
	if(document.cadastro.medico.value=="Selecione...")
	{
	alert("Preencha o campo Medico!");
	return false;
	}
else
	if(document.cadastro.dataexame.value=="")
	{
	alert("Preencha o campo Data do exame!");
	return false;
	}
else
	if(document.cadastro.falha.value=="")
	{
	alert("Preencha o campo Falha!");
	return false;
	}
else
	if(document.cadastro.impacto.value=="")
	{
	alert("Preencha o campo Impacto!!");
	return false;
	}
else	
if(document.cadastro.tratativa.value=="")
	{
	alert("Preencha o campo Tratativa!");
	return false;
	}
else
if(document.cadastro.chamado.value=="")
	{
	alert("Preencha o campo Chamado!");
	return false;
	}
else
	if(document.cadastro.chamado.value<"629999")
	{
	alert("N\u00famero de chamado inv\u00e1lido!");
	return false;
	}
else
		if(document.cadastro.chamado.value>"999999")
	{
	alert("N\u00famero de chamado inv\u00e1lido!");
	return false;
	}
else
	{
	alert("Seu cadastro foi realizado com sucesso!");
	return true;
	}
return true;
}
<!-- Fim do JavaScript que validará os campos obrigatórios! -->