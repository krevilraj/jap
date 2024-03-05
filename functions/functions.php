<?php
require_once(JAP_PATH . 'functions/function.competicao.php');
require_once(JAP_PATH . 'functions/function.equipas.php');
require_once(JAP_PATH . 'functions/function.juris.php');








/**
 * Get JAP Chart View
 */
function jap_avaliacoes_callback(){
    view('admin.avaliacoes.list');
}

/**
 * Get JAP Chart View
 */
function jap_avaliacoes_add_callback(){
    view('admin.avaliacoes.add');
}