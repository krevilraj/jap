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


        $equipa_id = sanitize_text_field(test_input($_POST['equipa_id']));
        $competicao_id = sanitize_text_field(test_input($_POST['competicao_id']));
        if (!isset($equipa_id)) {
            $error[] = 'Equipa ID não existe.';
        }

        global $wpdb;
        $table_name = TABLE_EQUIPAS;
        // Check if the competicao_id exists before attempting an update
        $equipa_exists = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $equipa_id));

        if ($equipa_exists) {

            if (isset($equipa_exists->competicao_id)) {
                if($equipa_exists->competicao_id != $competicao_id){
                    // delete old and insert new one;
                    if (current_user_can('manage_options')) {
                        global $wpdb;
                        $wpdb->delete(TABLE_EQUIPAS, array('id' => $equipa_id));
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
                }else{
                    if (isset($_POST['moment_id'])) {
                        global $wpdb;
                        $momento = $_POST['moment_id'];
                        foreach ($momento as $moment_index => $moment_item) {
                            $groupo = $_POST['groupo'];

                            $table_name = TABLE_EQUIPAS_MOMENTO;
                            // Check if the competicao_id exists before attempting an update
                            $equipa_groupo_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE equipas_id = %d AND moments_id = %d", $equipa_id, $moment_item));
                            if ($equipa_groupo_exists) {

                                $arr_input = [
                                    'equipas_id' => $equipa_id,
                                    'moments_id' => $moment_item,
                                    'groupo_id' => $groupo[$moment_index]
                                ];
                                $updated = $wpdb->update("$table_name", $arr_input, ["equipas_id" => $equipa_id, "moments_id" => $moment_item]);
                                $s_message = "Atualizado com sucesso!!!";
                            } else {

                                $user_id = get_current_user_id();
                                $table_name = TABLE_EQUIPAS_MOMENTO;
                                $user_id = get_current_user_id();
                                $wpdb->insert("$table_name", [
                                    'equipas_id' => $equipa_id,
                                    'moments_id' => $moment_item,
                                    'groupo_id' => $groupo[$moment_index],
                                    'user_id' => $user_id
                                ]);
                                $equipa_momento_id = $wpdb->insert_id;

                                if ($equipa_momento_id > 0) {
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
            } else {
                update_insert_equipa_competicao($equipa_id,$competicao_id,$_POST['moment_id'],$_POST['groupo']);
                $s_message = "Inserido com sucesso!!!";
            }
        }else{
            $e_message = "Não encontrado!!!";
        }

        $equipa = get_equipa_info($equipa_id);


    }
}

function insert_equipa_moment_group($equipa_id, $moment_id, $groupo)
{
    if (isset($moment_id)) {
        foreach ($moment_id as $index => $item) {
            global $wpdb;
            $table_name = TABLE_EQUIPAS_MOMENTO;
            $user_id = get_current_user_id();
            $wpdb->insert("$table_name", [
                'equipas_id' => $equipa_id,
                'moments_id' => $item,
                'groupo_id' => $groupo[$index],
                'user_id' => $user_id
            ]);
            $equipa_moment = $wpdb->insert_id;

            if ($equipa_moment > 0) {
                $s_message = "Inserido com sucesso!!!";

            } else {
                // Error message
                $wpdb_last_error = $wpdb->last_error;
                echo "Erro ao inserir dados para moment_id: Error message: $wpdb_last_error<br>";
            }
        }
    }
}
function update_insert_equipa_competicao($equipa_id,$competicao_id,$moment_id,$groupo){
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $arr_input = [
        'competicao_id' => $competicao_id,
    ];
    $updated = $wpdb->update("$table_name", $arr_input, ["id" => $equipa_id]);
    insert_equipa_moment_group($equipa_id, $moment_id, $groupo);
}
