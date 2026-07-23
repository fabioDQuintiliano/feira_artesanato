<?php
require_once('front_includes.php');

$pag = new Pagamento();
$ret = $pag->postBackAssinatura($_POST);

?>