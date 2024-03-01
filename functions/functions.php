<?php

/**
 * Get JAP Chart View
 */
function jap_equipas_callback(){
    view('admin.equipas.list');
}

/**
 * Get JAP Chart View
 */
function jap_equipas_add_callback(){
    global $wpdb;
    $competicao_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC", ""), ARRAY_A);

    view('admin.equipas.add',$competicao_list);
}

/**
 * Get JAP Groupo View
 */
function jap_groupo_callback(){
    view('admin.equipas.groupo');
}



/**
 * Get JAP Chart View
 */
function jap_juris_callback(){
    view('admin.juris.list');
}

/**
 * Get JAP Chart View
 */
function jap_juris_add_callback(){
    view('admin.juris.add');
}


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