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
//        pre($_POST);
//        exit();
        $equipa_id = sanitize_text_field(test_input($_POST['equipa_id']));
        $competicao_id = sanitize_text_field(test_input($_POST['competicao_id']));

        global $wpdb;
        $table_name = TABLE_EQUIPAS;
        $arr_input = [
            'competicao_id' => $competicao_id,
        ];
        $updated = $wpdb->update("$table_name", $arr_input, ["id" => $equipa_id]);

        if(isset($_POST['moment_id'])){
            foreach ($_POST['moment_id'] as $index => $item){
                global $wpdb;
                $table_name = TABLE_EQUIPAS_MOMENTO;
                $user_id = get_current_user_id();
                $wpdb->insert("$table_name", [
                    'equipas_id' => $equipa_id,
                    'moments_id' => $item,
                    'groupo_id' => $_POST['groupo'][$index],
                    'user_id' => $user_id
                ]);
                $competicao_id = $wpdb->insert_id;

                if ($competicao_id > 0) {
                    $s_message = "Inserido com sucesso!!!";

                } else {
                    // Error message
                    $wpdb_last_error = $wpdb->last_error;
                    echo "Erro ao inserir dados para moment_id: Error message: $wpdb_last_error<br>";
                }
            }
        }


    }
}