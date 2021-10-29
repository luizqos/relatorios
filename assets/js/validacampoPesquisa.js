function validaCampoPesquisa()
{
if((document.pesquisa.chamado.value=="") & (document.pesquisa.dataexame.value!="") & (document.pesquisa.datarec.value!=""))
	{
	alert("Errado!");	
    return false;
	}
return true;
}
<!-- Fim do JavaScript que validará os campos obrigatórios! -->