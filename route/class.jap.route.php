<?php
if (!class_exists('Jap_Route')) {
  class Jap_Route
  {
    function __construct()
    {
      add_action('admin_menu', array($this, 'jap_competicao'));
    }

    function jap_competicao()
    {


      add_menu_page(
        'Competição',
        'Competição',
        'edit_published_posts',
        'competicao',
        'jap_competicao_callback',
        'dashicons-megaphone',24);

      add_submenu_page('competicao',
        'Competição',
        'Toda a competição',
        'edit_published_posts',
        'competicao',
        'jap_competicao_callback');

      add_submenu_page('competicao',
        'Adicionar competição',
        'Adicionar competição',
        'manage_options',
        'add-competicao',
        'jap_competicao_add_callback');
        add_submenu_page(null,
            'Excluir competição',
            'Excluir competição',
            'manage_options',
            'delete-competicao',
            'jap_delete_competicao');
        add_submenu_page(null,
            'Editar competição',
            'Editar competição',
            'manage_options',
            'edit-competicao',
            'jap_edit_competicao');



        add_menu_page(
            'Equipas',
            'Equipas',
            'edit_published_posts',
            'equipas',
            'jap_equipas_callback',
            'dashicons-groups',24);

        add_submenu_page('equipas',
            'Equipas',
            'Toda a equipas',
            'edit_published_posts',
            'equipas',
            'jap_equipas_callback');

        add_submenu_page('equipas',
            'Equipes e grupos',
            'Equipes e grupos',
            'manage_options',
            'groupo',
            'jap_groupo_callback');

        add_submenu_page('equipas',
            'Adicione equipes por momentos',
            'Adicione equipes por momentos',
            'manage_options',
            'add-equipas',
            'jap_equipas_add_callback');
        add_submenu_page(null,
            'Excluir equipas',
            'Excluir equipas',
            'manage_options',
            'delete-equipas',
            'jap_delete_equipa');
        add_submenu_page(null,
            'Editar equipa',
            'Editar equipa',
            'manage_options',
            'edit-equipa',
            'jap_edit_equipa_groupo');
        add_submenu_page(null,
            'Excluir equipa',
            'Excluir equipa',
            'manage_options',
            'delete-equipa_competicao_list',
            'jap_delete_equipa_competicao');
        add_submenu_page(null,
            'Editar equipa',
            'Editar equipa',
            'manage_options',
            'edit-equipa_competicao_list',
            'jap_edit_equipa_competicao');





        add_menu_page(
            'Juris',
            'Juris',
            'edit_published_posts',
            'juris',
            'jap_juris_callback',
            'dashicons-businessperson',24);

        add_submenu_page('juris',
            'Juris',
            'Toda a juris',
            'edit_published_posts',
            'juris',
            'jap_juris_callback');

        add_submenu_page('juris',
            'Adicionar juris',
            'Adicionar juris',
            'manage_options',
            'add-juris',
            'jap_juris_add_callback');

        add_submenu_page(null,
            'Excluir juris',
            'Excluir juris',
            'manage_options',
            'delete-juris',
            'jap_delete_juris');
        add_submenu_page(null,
            'Editar juris',
            'Editar juris',
            'manage_options',
            'edit-juris',
            'jap_edit_juris');


        add_menu_page(
            'Avaliações',
            'Avaliações',
            'edit_published_posts',
            'avaliacoes',
            'jap_avaliacoes_callback',
            'dashicons-clipboard',24);

        add_submenu_page('avaliacoes',
            'Avaliações',
            'Toda a avaliacoes',
            'edit_published_posts',
            'avaliacoes',
            'jap_avaliacoes_callback');

        add_submenu_page('avaliacoes',
            'Adicionar avaliacoes',
            'Adicionar avaliacoes',
            'manage_options',
            'add-avaliacoes',
            'jap_avaliacoes_add_callback');


    }

  }
}
