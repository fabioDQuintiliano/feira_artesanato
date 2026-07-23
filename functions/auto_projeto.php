<?php
function inProjetos(){
    $projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('listar');

    return implode(',', $projetosPermitidos);
}


function fn_pos_insert_projeto($id){
   
    if($_GET['fromtask']){
        header("location:".ROOT);
    }
}