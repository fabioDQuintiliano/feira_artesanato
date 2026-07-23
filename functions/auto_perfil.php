<?php
function auto_pre_insert_perfil(){
	return ajusta_permissao();
}
function auto_pre_update_perfil(){
	return ajusta_permissao();
}

function ajusta_permissao(){
	
	$permissoses['menu'] = array();
	$permissoses['add'] = array();
	$permissoses['edit'] = array();
	$permissoses['del'] = array();
	$permissoses['view'] = array();
	$permissoses['list'] = array();
	$permissoses['bt_adicional'] = array();
	
	if($_POST['menu']!= '')
	$permissoses['menu'] = $_POST['menu'];
	
	if($_POST['add']!= '')
	$permissoses['add'] = $_POST['add'];
	
	if($_POST['edit']!= '')
	$permissoses['edit'] = $_POST['edit'];
	
	if($_POST['del']!= '')
	$permissoses['del'] = $_POST['del'];
	
	if($_POST['view']!= '')
	$permissoses['view'] = $_POST['view'];
	
	if($_POST['list']!= '')
	$permissoses['list'] = $_POST['list'];
	
	if($_POST['bt_adicional']!= '')
	$permissoses['bt_adicional'] = $_POST['bt_adicional'];
	
	$_POST['permissoes'] = serialize($permissoses);
		
	
	
	
}
?>