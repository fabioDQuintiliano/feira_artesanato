<?php
if(!logado_no_perfil(2)){
	//header("location:".ROOT);
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="ROOT/images/ico.png" />

    <title>Tricô. O Queridinho da Moda.</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/creative.css" rel="stylesheet">
    <link href="css/admloja.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/filepond/dist/filepond.min.css">
    <link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css">



    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122371429-1"></script>
    <script>
      var ROOT = '<?=ROOT?>';
    </script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-122371429-1');
    </script>

    (-((--HEAD_INCLUDES--))-)



    <script src="https://unpkg.com/filepond-plugin-image-preview"></script>
    <script src="https://unpkg.com/filepond"></script>

    <script src="ROOT/script/jquery-1.9.0.js"></script>
    <script src="ROOT/vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="ROOT/script/script_adminLojas.js"></script>

    <script src="ROOT/script/bootbox.all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.js"></script>
    <script src="<?php echo ROOT; ?>script/vue3-bridge.js"></script>
    <script src="ROOT/script/v-money.js"></script>



    <script src="https://unpkg.com/vue-filepond@6.0.3/dist/vue-filepond.min.js"></script>


  </head>

  <body id="page-top">



    <?php //loadObj('objTermos');?>
    <?php loadObj('obj_menuadmloja');?>



    <div class="conteudo_principal_painel">

      <div class="container-fluid">
        [CONTENT-PLACE]
      </div>


    </div>

  </body>

</html>
