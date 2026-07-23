<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema - Admin</title>
<link href="<?php echo ROOT?>system/css-system.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ROOT?>css/prettyPhoto.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo ROOT?>script/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT?>script/prettyPhoto.js"></script>
<script type="text/javascript" src="<?php echo ROOT?>script/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo ROOT?>system/script.js"></script>
<link href="<?php echo ROOT?>css/jquery.ui.all.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">

$(document).ready(function(){
	$("a[rel^='prettyPhoto']").prettyPhoto({
		social_tools:''

		
		
		});
});

</script>
</head>

<body>
	<div id="head_menu_system">
    	<div id="head_menu_system_inner">
        	
            <!--div class="itens_menu_system">
            	<a href="<?=ROOT?>system-inicio">Inicio</a>
            </div-->
            <div class="itens_menu_system">
            	<a href="<?=ROOT?>system-form">Formulários</a>
            </div>
            <div class="itens_menu_system">
            	<a href="<?=ROOT?>system-page_icons">Icones</a>
            </div>
            
        </div>
    </div>

