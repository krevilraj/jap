<?php
$validation = true;
$error = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['submit'])) {
        if (isset($_POST['jap_juris_nonce'])) {
            if (!wp_verify_nonce($_POST['jap_juris_nonce'], 'jap_juris_nonce')) {
                return;
            }
        }



        $juri_id = sanitize_text_field(test_input($_POST['juri_id']));
        if (!isset($juri_id)) {
            $error[] = 'Juri ID does not exist.';
            wp_die();
        }

        global $wpdb;
        $table_name = TABLE_USERS;
        // Check if the competicao_id exists before attempting an update
        $juri_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE id = %d", $juri_id));



        if ($juri_exists) {
            $nome = sanitize_text_field(test_input($_POST['nome']));
            $email = sanitize_text_field(test_input($_POST['email']));
            $competicao_id = sanitize_text_field(test_input($_POST['competicao_id']));
            $old_juri_data = get_user_info($juri_id);

            // Check if password is set in $_POST
            if (isset($_POST['password']) && trim($_POST['password'])!="") {
                $password = sanitize_text_field($_POST['password']);
                // Update password using wp_set_password
                wp_set_password($password, $juri_id);
            }
            $old_competicao_id = get_user_meta($juri_id, 'competicao_id', true);
            if($competicao_id != $old_competicao_id){
                update_user_meta($juri_id, 'competicao_id', $competicao_id);
                global $wpdb;
                $wpdb->delete(TABLE_JURIS_MOMENTO, array('user_id' => $juri_id));



            }else{
                update_user_meta($juri_id, 'competicao_id', $competicao_id);
                if($nome == $old_juri_data->user_login && $email == $old_juri_data->user_email){
                    $s_message = "Atualização bem sucedida";
                }else{
                    $arr_input = [
                        'user_login' => $nome,
                        'user_email' => $email
                    ];

                    $updated = $wpdb->update($table_name, $arr_input, ['id' => $juri_id]);
                    if ($updated) {
                        $s_message = "Atualização bem sucedida";
                    } else {
                        $e_message = "Atualização falhou". $wpdb->last_error;
                    }

                }
            }

        }
        $juri = get_user_info($juri_id);
    }
}