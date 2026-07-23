<?php
	$menu = new \Sistema\Admin\Menu();
    $dados = $menu->getMenu();

    //loadObj('cp_lista_projetos');
?>




	<div id="admMenu" class="collapse navbar-collapse  w-auto  <?php /*max-height-vh-100 h-100 */?>" id="sidenav-collapse-main">
	    <ul class="navbar-nav">
	        <li class="nav-item nav-item-principal" v-for="item in dados">

	        	<a class="nav-link  text-decoration-none item-categoria-menu">
		         	
		            <span class="nav-link-text ms-1  nav-item-principal-text ">{{item.item.nome}}</span>
		        </a>


		        <ul class="navbar-nav ">
	        		<li class="nav-item" v-for="sub in item.subitens">
	        			<a class="nav-link  text-decoration-none" :href="'ROOT/adm-home?item='+sub.form" >
				            <div class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
				            	<i v-if="sub && sub.icon" v-bind:class="[sub.icon,'text-dark','icon-menu']"></i>
				            </div>
				            <span class="nav-link-text ms-1">{{sub.item}}</span>
				        </a>

	        		</li>
	        	</ul>
	        </li>

	    </ul>



	    <!--a class="nav-link  text-decoration-none item-categoria-menu item-categoria-menu-tl">
		         	
            <span class="nav-link-text ms-1  nav-item-principal-text ">Projetos</span>
        </a>
	    <cp_lista_projetos / -->

	</div>







<script>
var app = new Vue({
    el: '#admMenu',
    data: {

        codigo:'',
        dados:<?=json_encode($dados)?>

    },
    mounted: function () {
        
    	console.log(this.dados)
    
    },
    methods:{
        
    	
        
    }
})
</script>

<style scoped>
.navbar-vertical.navbar-expand-xs .navbar-collapse{
	height: auto;
}
.navbar-vertical.navbar-expand-xs .navbar-nav .nav-link{
	padding-top: 0px;
	padding-bottom: 0px;
	padding-left: 10px;
	margin: 0px;
}
.icon-menu{
	font-size: 14px !important;
}
.item-categoria-menu {
	position: relative;
}
.nav-item-principal{
	margin-bottom: 20px;
}
.nav-item-principal-text{

	font-weight: 100;
}
#admMenu .list-group-item{
	background: transparent;
	padding-left: 15px !important;
}
.item-categoria-menu-tl{
	padding-left: 10px !important;
}

</style>

<? /*

<script>

$(function(){

	

	$('.itens_menu_admin').click(function(){

		

		$('.itens_menu_admin').removeClass('ativaHide');

		$(this).addClass('ativaHide');

		

		$('.topBarMenu').each(function(){

			if(!$(this).parent('.itens_menu_admin').hasClass('ativaHide')){

				$(this).fadeOut( 100 );

			}

		});

		

		var id_submenu = $(this).attr('sub');	

		$('.submenu_admin').hide();

		$('#'+id_submenu).show();

		

		setTimeout(function(){setSizeWindow()},100);

		

	})

	


	$('.itens_menu_admin').mouseover(function(){

		$(this).find('.topBarMenu').fadeIn( 200 );	

		

	}).mouseleave(function(){

		if(!$(this).hasClass('ativaHide')){

			$(this).find('.topBarMenu').fadeOut( 100 );

		}

	});

	

})



</script>

<div id="menu_admin">



    

    <?php


    


	global $MAP;

	require_once('tables/_admin_menu.php');

	//$configTableList = getInfoItem($_GET[':item']);

	

	

	function orderMenu($a, $b)

	{

		return strnatcmp($a['order_by'], $b['order_by']);

	}



	uasort($INFO_MENU, 'orderMenu');

	$mainManu = $INFO_MENU;

	

    $cores = array('#0ebd8f','#f2c40e','#88d878','#d87878','#74c6c3','#34495e','#ba78d8','#d35400','#7f8c8d');

	

    $conta_item=0;

	$listSup = array();	



	foreach($mainManu as $k=>$v):

		

		$contaSubmenu=0;

		if(count($v['itens'])>0)foreach($v['itens'] as $j=>$subMenu):

			$MAP['infoPages'][$subMenu['link']]=$subMenu;

			if(!in_array($subMenu['link'],$PERFIL_PERMISSOES['menu'])){continue;}

			$contaSubmenu++;

		endforeach;

		

		if($contaSubmenu==0){continue;}

			$listSup[]=$k;	

        echo '<div class="itens_menu_admin it_menu_'.$conta_item.' '.($v["itens"][removeCaracteres($configTableList->nome)]['form'] == $_GET[':item'] && $_GET[':item'] != ''?'ativaHide':'').'" sub="menu'.removeCaracteres($k).'">

                <div class="topBarMenu" style="background:'.$cores[$conta_item].'; '.($v["itens"][removeCaracteres($configTableList->nome)]?'display:block':'').'"></div>';

                

				if($v['ico']!=''):

					echo $k;

				else:

					echo $k;

				endif;

				

        echo '</div>';

			  

        $conta_item += 1;

	endforeach;









    $conta_item=0;

	

	foreach($mainManu as $k=>$v):

		if(!in_array($k,$listSup)){continue;}

		



	//	echo '<div class="submenu_admin" id="menu'.removeCaracteres($k).'"  style="background-color:'.$cores[$conta_item].'; '.

	//		($v["itens"][removeCaracteres($configTableList->nome)]['form'] == $_GET[':item'] && $_GET[':item'] != ''?'display:block':'').'">';

		echo '<div class="submenu_admin '.($v["itens"][removeCaracteres($configTableList->nome)]['form'] == $_GET[':item'] && $_GET[':item'] != ''?'showMenuItem':'').'" id="menu'.removeCaracteres($k).'"  style="background-color:'.$cores[$conta_item].';">';

		echo '<div class="inner_submenu_admin">';





			uasort($v['itens'], 'orderMenu');

			foreach($v['itens'] as $j=>$subMenu):

			

				if(!in_array($subMenu['link'],$PERFIL_PERMISSOES['menu'])){continue;}

				

				echo '<a href="ROOT/adm-home?item='.$subMenu['form'].'"> <div class="itens_submenu_admin">'.$subMenu['item'].'</div></a>';

			

			endforeach;	

		echo '</div>';

		echo '</div>';

        $conta_item += 1;

	endforeach;



?>
<div class="btn btn-sair">
	<a href="ROOT/adm-logout">
		<img src="ROOT/images/admin/logout.png">
		Sair
	</a>
</div>
    

</div>



*/ ?>