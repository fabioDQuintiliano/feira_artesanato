<?php
function fn_pos_insert_postagem($id){

	$classBlog = new \Sistema\Blog();
	$classBlog->getTags($id);
	
}
function fn_pos_update_postagem($id){
	$classBlog = new \Sistema\Blog();
	$classBlog->getTags($id);

	
}
