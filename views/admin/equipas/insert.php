<?php

$validation = true;
$error = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['submit'])) {
        if (isset($_POST['jap_equipas_nonce'])) {
            if (!wp_verify_nonce($_POST['jap_equipas_nonce'], 'jap_equipas_nonce')) {
                return;
            }
        }
        $equipas_nome = sanitize_text_field(test_input($_POST['nome']));
        global $wpdb;
        $table_name = TABLE_EQUIPAS;
        $user_id = get_current_user_id();
        $wpdb->insert("$table_name", [
            'nome' => $equipas_nome,
            'user_id' => $user_id
        ]);
        $equipas_id = $wpdb->insert_id;

        if ($equipas_id > 0) {
            if(isset($_POST['groupo'])){
                foreach ($_POST['groupo'] as $data) {
                    $table_name = TABLE_GROUPO;
                    $wpdb->insert("$table_name", [
                        'equipas_id' => $equipas_id,
                        'nome' => $data,
                        'user_id' => $user_id
                    ]);
                }
            }
            $equipas_list = get_all_equipa();
            $s_message = "Nova equipe adicionada com sucesso";
        }else{
            $e_message = "Desculpe, não é possível adicionar esta equipe";
        }
    }
}
