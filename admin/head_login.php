<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, user-scalable=no">



<title>Sistema - Admin</title>

<script>
	var ROOT = 'ROOT/';
</script>

<link rel="shortcut icon" href="<?php echo ROOT?>images/ico.png" />



<link href="<?php echo ROOT?>admin/css-admin.css" rel="stylesheet" type="text/css" />

<link href="<?php echo ROOT?>admin/login.css" rel="stylesheet" type="text/css" />

<link href="<?php echo ROOT?>css/prettyPhoto.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo ROOT?>script/jquery-1.8.1.min.js"></script>

<script type="text/javascript" src="<?php echo ROOT?>script/prettyPhoto.js"></script>

<script type="text/javascript" src="<?php echo ROOT?>script/script.js"></script>

<script type="text/javascript" src="<?php echo ROOT?>script/Vague.js"></script>

<script type="text/javascript">

<?php if($_SESSION['resposta_ok']){?>
	setTimeout(function(){
		alerta("<?php echo $_SESSION['resposta_ok'];?>");
	},500);
<?php unset($_SESSION['resposta_ok']);}?>	

$(document).ready(function(){

	$("a[rel^='prettyPhoto']").prettyPhoto({

		social_tools:''



		

		

		});

		

		

	var vague = $("#contentLoginBlur").Vague({intensity:3});

	vague.blur();

});



</script>

</head>



<body>

    <div id="contentLoginBlur"></div><!-- <div class="contentLoginBlur"> -->

	<div class="contentLogin">

    

