<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title><?= PROJETO_NOME ?></title>

<link rel="shortcut icon" href="ROOT/images/ico.png" />
<script src="ROOT/script/jquery-1.9.0.js"></script>
<script src="ROOT/script/jquery-migrate-1.0.0.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
	integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
	crossorigin="anonymous"></script>

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>



<script type="text/javascript" src="ROOT/script/prettyPhoto.js"></script>

<script type="text/javascript" src="ROOT/script/jquery.meio.mask.js"></script>

<script type="text/javascript" src="ROOT/script/jquery-ui.js"></script>

<script type="text/javascript" src="ROOT/script/date.js"></script>

<script type="text/javascript" src="ROOT/script/script.js"></script>

<script type="text/javascript" src="ROOT/script/Chart.min.js"></script>
<script type="text/javascript" src="ROOT/script/leader-line.min.js"></script>


<script src="ROOT/script/bootbox.all.js"></script>
<script src="ROOT/script/vue.js"></script>
<script src="ROOT/script/v-money.js"></script>
<script src="ROOT/js/toast/jquery.toast.js"></script>

<link href="ROOT/js/toast/jquery.toast.css" rel="stylesheet" type="text/css" />

<script src="ROOT/admin/template/soft-ui-dashboard-main/assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="https://kit.fontawesome.com/3f6cf13266.js" crossorigin="anonymous"></script>


<link href="ROOT/admin/template/soft-ui-dashboard-main/assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet"
	type="text/css" />
<link href="ROOT/admin/css-admin.css" rel="stylesheet" type="text/css" />

<link href="ROOT/css/jquery.ui.all.css" rel="stylesheet" type="text/css" />

<link href="ROOT/css/prettyPhoto.css" rel="stylesheet" type="text/css" />

<link href="ROOT/css/animate.css" rel="stylesheet" type="text/css" />



<script src="//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js"></script>
<!-- CDNJS :: Vue.Draggable (https://cdnjs.com/) -->
<script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js"></script>

(-((--HEAD_INCLUDES--))-)

<script src="https://unpkg.com/v-tooltip@2.0.0"></script>
<?php

global $MAP;

$urlAnterior = $_SESSION['urlAnterior'];

$MAP['urlAnterior'] = $urlAnterior;

$_SESSION['urlAnterior'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];



?>
<style>

</style>


<script>
	Vue.use(VTooltip)



	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	var ROOT = 'ROOT/';


	$(function () {







		$("a[rel^='prettyPhoto']").prettyPhoto({ social_tools: '' });





		$(".itens_menu_admin").on('click', function () {

			if ($(this).hasClass('ativaHide') == true) {

				if ($("#container_principal").css('display') == 'none') {

					$("#container_principal").fadeIn(100, function () { setSizeWindow(); });

				} else {

					$("#container_principal").fadeOut(100, function () { setSizeWindow(); });

				}

			} else {

				$("#container_principal").fadeOut(100, function () { setSizeWindow(); });

			}

		});













		$(".tableList thead th").each(function () {

			if ($(this).html() == "Código") {

				$(this).css('width', 50);

			}



		});





		$(".Datepicker").datepicker({

			showOn: "button",

			dateFormat: 'dd/mm/yy',

			dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],

			dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],

			dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],

			monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],

			monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],

			nextText: 'Próximo',

			prevText: 'Anterior',

			numberOfMonths: 3,

			buttonImage: "<?= ROOT ?>images/admin/calendar.gif",

			buttonImageOnly: true

		});



		$(".mask_type_data").datepicker({

			showOn: "button",

			dateFormat: 'dd/mm/yy',

			dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],

			dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],

			dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],

			monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],

			monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],

			nextText: 'Próximo',

			prevText: 'Anterior',

			numberOfMonths: 3,

			buttonImage: "<?= ROOT ?>images/admin/calendar.gif",

			buttonImageOnly: true

		});

		$('.mask_type_data').setMask('99/99/9999');

		$('.mask_type_hora').setMask('99:99');

		$('.mask_type_cep').setMask('99.999-999');

		$('.mask_type_cpf').setMask('999.999.999-99');

		$('.mask_type_rg').setMask('**.***.***-*');

		$('.mask_type_cnpj').setMask('99.999.999/9999-99');

		$('.mask_type_decimal').setMask('decimal');

		//$('.mask_type_tel').setMask('(99)9999-9999');



		$('.mask_type_tel').live('keyup', function () {

			var valor = $(this).val();

			valor = valor.replace('(', '').replace(/\)/gi, '').replace(/\-/gi, '');

			if (valor.length > 11) {

				$(this).unsetMask();

				$(this).setMask('(99) 9-9999-9999');

			} else {

				$(this).unsetMask();

				$(this).setMask('(99) 9999-99999');

			}

		})



	});



	var FUNCTION;



	function conf(pergunta, fn) {

		FUNCTION = fn;

		if (pergunta != null) {



			$("#quest_box").html(pergunta);

			$("#confirm_box").show();



		}

		return false;

	}

	$(function () {

		//



		$(".confirm_confirmar").click(function () {

			if (FUNCTION()) {

				FUNCTION = '';

			}



			$("#confirm_box").hide();

			$("#quest_box").html('');

			return false;

		});



		$("#confirm_cancelar").click(function () {

			$("#confirm_box").hide();

			$("#quest_box").html('');

			return false;

		});

	});

	function wait() {

		$("#waitLoad").show();

	}
	function loadShow() {
		$("#waitLoad").show();

	}
	function loadHide() {
		$("#waitLoad").hide();

	}

</script>