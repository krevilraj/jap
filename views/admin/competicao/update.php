<?php
$validation = true;
$error = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['submit'])) {
        if (isset($_POST['jap_competicao_nonce'])) {
            if (!wp_verify_nonce($_POST['jap_competicao_nonce'], 'jap_competicao_nonce')) {
                return;
            }
        }

//        pre($_POST);

        $competicao_id = sanitize_text_field(test_input($_POST['competicao_id']));
        if(!isset($competicao_id)){
            $error[] = 'Competicao ID does not exist.';
        }

        global $wpdb;
        $table_name = TABLE_COMPETICAO;
        // Check if the competicao_id exists before attempting an update
        $competicao_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE id = %d", $competicao_id));



        if ($competicao_exists) {

            $user_id = get_current_user_id();
            $msg = '';
            $nome = sanitize_text_field(test_input($_POST['nome']));
            $status = sanitize_text_field(test_input($_POST['status']));
            $image = sanitize_text_field(test_input($_POST['image']));
            $arr_input = [
                'nome' => $nome,
                'image' => $image,
                'status' => $status,
                'user_id' => $user_id
            ];
            $updated = $wpdb->update("$table_name", $arr_input, ["id" => $competicao_id]);


            if (false === $updated) {
                $e_message = "Algo deu errado.";
            } else {
                if (isset($_POST['momento_name']) && is_array($_POST['momento_name']) && !empty($_POST['momento_name'])) {
                    $momento_arr = $_POST['momento_name'];
                    $momento_id_arr = $_POST['momento_id'];
                    $total_momento = count($momento_arr);
                    for ($parent_index = 0; $parent_index < $total_momento; $parent_index++) {
                        $temp_moment_title = $momento_arr[$parent_index][0];

                        $table_name_momento = TABLE_MOMENTO;
                        if(isset($momento_id_arr[$parent_index][0])){
                            $temp_moment_id = $momento_id_arr[$parent_index][0];
                            $arr_input = [
                                'title' => $temp_moment_title,
                            ];
                            $updated = $wpdb->update("$table_name_momento", $arr_input, ["id" => $temp_moment_id]);
                        }else{
                            $table_name = TABLE_MOMENTO;
                            $wpdb->insert("$table_name", [
                                'competicao_id' => $competicao_id,
                                'title' => $temp_moment_title,
                                'user_id' => $user_id
                            ]);
                            $temp_moment_id = $wpdb->insert_id;
                        }


                        $observacao_arr = $_POST['observacao'][$parent_index];
                        $peso_da_nota_arr = $_POST['peso_da_nota'][$parent_index];
                        $o_que_avaliamos_arr = $_POST['o_que_avaliamos'][$parent_index];

                        foreach ($observacao_arr as $index => $value) {

                            if(!isset($_POST['observacao_id'][$parent_index])){
                                $table_name = TABLE_MOMENTO_META;
                                $arr_input = [
                                    'moments_id' => $temp_moment_id,
                                    'title' => $observacao_arr[$index],
                                    'peso_da_nota' => $peso_da_nota_arr[$index],
                                    'o_que_avaliamos' => $o_que_avaliamos_arr[$index],
                                    'user_id' => $user_id,
                                ];
                                $wpdb->insert("$table_name", $arr_input);
                            }else{
                                $observacao_id_arr = $_POST['observacao_id'][$parent_index];
                                if(isset($observacao_id_arr[$index])){
                                    $table_name = TABLE_MOMENTO_META;
                                    $arr_input = [
                                        'title' => $observacao_arr[$index],
                                        'peso_da_nota' => $peso_da_nota_arr[$index],
                                        'o_que_avaliamos' => $o_que_avaliamos_arr[$index]
                                    ];
                                    $updated = $wpdb->update("$table_name", $arr_input, ["id" => $observacao_id_arr[$index]]);

                                }else{
                                    $arr_input = [
                                        'moments_id' => $temp_moment_id,
                                        'title' => $observacao_arr[$index],
                                        'peso_da_nota' => $peso_da_nota_arr[$index],
                                        'o_que_avaliamos' => $o_que_avaliamos_arr[$index],
                                        'user_id' => $user_id,
                                    ];
                                    $wpdb->insert("$table_name", $arr_input);


                                }
                            }


//                            pre($arr_input);
//                            echo '</br>';

                        }
//                        echo '<br><br><br>';

                    }
                }

                $competicao = get_competicao($competicao_id);
                $data['momentos_list'] = get_momento($competicao_id);
                $s_message = "Success ";
            }




        } else {
            // Handle the case where the competicao_id does not exist
            $error[] = 'Competicao ID does not exist.';
            echo 'update err';
        }



    }
}
