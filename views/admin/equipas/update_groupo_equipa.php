<?php
$validation = true;
$error = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['submit'])) {
        if (isset($_POST['jap_groupo_nonce'])) {
            if (!wp_verify_nonce($_POST['jap_groupo_nonce'], 'jap_groupo_nonce')) {
                return;
            }
        }


//        pre($_POST);

        $id = sanitize_text_field(test_input($_POST['id']));
        if (!isset($id)) {
            $error[] = 'Equipas ID does not exist.';
        }

        global $wpdb;
        $table_name = TABLE_EQUIPAS;
        // Check if the competicao_id exists before attempting an update
        $equipa_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE id = %d", $id));


        if ($equipa_exists) {
            $user_id = get_current_user_id();
            $msg = '';
            $nome = sanitize_text_field(test_input($_POST['nome']));

            $arr_input = [
                'nome' => $nome,
            ];
            $updated = $wpdb->update("$table_name", $arr_input, ["id" => $id]);


            if (false === $updated) {
                $e_message = "Algo deu errado.";
            } else {
                if (isset($_POST['groupo']) && is_array($_POST['groupo']) && !empty($_POST['groupo'])) {
                    $groupo_arr = $_POST['groupo'];
                    $table_name_groupo = TABLE_GROUPO;
                    foreach ($groupo_arr as $index => $item) {
                        if (isset($_POST['groupo_id'][$index][0])) {
                            $temp_groupo_id = $_POST['groupo_id'][$index][0];
                            $arr_input = [
                                'nome' => $item[0],
                            ];
                            $updated = $wpdb->update("$table_name_groupo", $arr_input, ["id" => $temp_groupo_id]);
                            $s_message = "Atualizado com sucesso!!";
                        }else{
                            $data = array(
                                'nome' => $item[0],
                                'equipas_id' => $id,
                                'user_id' => $user_id
                            );
                            $table_name = TABLE_GROUPO;
                            $result = $wpdb->insert($table_name, $data);
                            $s_message = "Atualizado com sucesso!!";
                            if ($result === false) {
                                // An error occurred, you can log or display the error message
                                $error_message = $wpdb->last_error;
                                error_log("Error inserting into $table_name: $error_message");
                                // Optionally, you can display the error to the user
                                wp_die("Error inserting into $table_name: $error_message");
                            } else {
                                // Insert successful
                                $temp_moment_id = $wpdb->insert_id;
                            }

//                            jap_redirect('edit-equipa&equipa_id='.$id);
                        }
                    }
                    $equipa = get_equipas($id);
                    $data['groupo'] = get_equipas_detail($id);
                }
            }
        }




    }
}
