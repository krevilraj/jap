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

        $nome = sanitize_text_field(test_input($_POST['nome']));
        $status = sanitize_text_field(test_input($_POST['status']));
        $image = "";


        global $wpdb;
        $table_name = TABLE_COMPETICAO;
        $user_id = get_current_user_id();
        $wpdb->insert("$table_name", [
            'nome' => $nome,
            'image' => $image,
            'status' => $status,
            'user_id' => $user_id
        ]);
        $competicao_id = $wpdb->insert_id;

        if ($competicao_id > 0) {
            if (isset($_POST['momento_name']) && is_array($_POST['momento_name']) && !empty($_POST['momento_name'])) {
                $momento_arr = $_POST['momento_name'];
                $total_momento = count($momento_arr);
                for ($parent_index = 0; $parent_index < $total_momento; $parent_index++) {
                    foreach ($momento_arr[$parent_index] as $moment_title) {

                        $table_name = TABLE_MOMENTO;
                        $wpdb->insert("$table_name", [
                            'competicao_id' => $competicao_id,
                            'title' => $moment_title,
                            'user_id' => get_current_user_id()
                        ]);
                        $momento_id = $wpdb->insert_id;
                        if ($momento_id > 0) {
                            $observacao_arr = $_POST['observacao'][$parent_index];
                            $peso_da_nota_arr = $_POST['peso_da_nota'][$parent_index];
                            $o_que_avaliamos = $_POST['o_que_avaliamos'][$parent_index];
                            foreach ($observacao_arr as $index => $value) {
                                $table_name = TABLE_MOMENTO_META;
                                $wpdb->insert("$table_name", [
                                    'moments_id' => $momento_id,
                                    'title' => $observacao_arr[$index],
                                    'peso_da_nota' => $peso_da_nota_arr[$index],
                                    'o_que_avaliamos' => $o_que_avaliamos[$index],
                                    'user_id' => $user_id,
                                ]);

                                if ($wpdb->insert_id > 0) {
                                    reset_field();
                                    $s_message = "Obrigado!!! você estará em breve responder do admin";
                                } else {
                                    $e_message = "Algo deu errado.";
                                }
                            }
                        } else {
                            $e_message = "Algo deu errado.123";

                        }
                    }
                }
            } else {

                $e_message = "Por favor, preencha todos os campos";
            }
        } else {
            $e_message = "Algo deu errado.";
        }


    }

}
?>
<div class="message__box">
    <?php if (isset($s_message)): ?>
        <div class="success-message"><?php echo $s_message ?></div>
    <?php endif; ?>
    <?php if (isset($e_message)): ?>
        <div class="error-message"><?php echo $e_message ?></div>
    <?php endif; ?>
</div>
