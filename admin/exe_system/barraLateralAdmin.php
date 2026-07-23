<script>
function setSizeWindow(){
	var largSize = $(window).width();	
	$("#container_geral_admin").css('width',(largSize -50));
	
}

$(function(){
		
	/*formata a largura da página*/
	setSizeWindow();
	$(window).resize(function(){
		setSizeWindow();
	})
	

	
	$("#barraLateralAdmin").mouseover(function(){
		$("#barraLateralAdmin").animate({
				width:'160px'
			},200);
			
		$('.linhaBarraLateralAdminICO').animate({
				width:'25px'
			},200)
		$('.linhaBarraLateralAdmin').animate({
				height:'80px'
			},200)
		
	}).mouseleave(function(){
		$("#barraLateralAdmin").animate({
				width:'50px'
			},100);
			
		$('.linhaBarraLateralAdminICO').animate({
				width:'40px'
			},100);
		$('.linhaBarraLateralAdmin').animate({
				height:'45px'
			},100)
		
	});	
});
</script>
<div id="barraLateralAdmin">

	<div class="logoutSistema" onclick="wait(); location.href='ROOT/admsite-logout'">
    	Sair do sistema
    </div><!-- logoutSistema -->

	<? /* -----------------------------------------------------------------------------------------*/?>
	<div class="linhaBarraLateralAdmin">
    	<img src="<?=ROOT?>images/admin/suporte.png" class="linhaBarraLateralAdminICO" />
		<div class="linhaBarraLateralAdminTXT">
        	Exemplo
        	<div class="linhaBarraLateralAdminLEG">
            	É possível adicionar mais itens à essa barra atraveés da página "barraLateralAdmin.php"
            </div><!-- linhaBarraLateralAdminLEG -->
        </div><!-- linhaBarraLateralAdmin -->
    </div><!-- linhaBarraLateralAdmin -->
	<? /* -----------------------------------------------------------------------------------------*/?>

</div><!-- barraLateralAdmin -->