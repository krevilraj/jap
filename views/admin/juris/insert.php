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


        $nome = sanitize_text_field(test_input($_POST['nome']));
        $password = sanitize_text_field(test_input($_POST['password']));
        $email = sanitize_text_field(test_input($_POST['email']));
        $competicao_id = sanitize_text_field(test_input($_POST['competicao_id']));

        // Set the user data
        $user_data = array(
            'user_login' => $nome,
            'user_pass' => $password,
            'user_email' => $email,
            'role' => 'author', // Assign the 'author' role to the user
        );

        // Insert the user
        $user_id = wp_insert_user($user_data);

        if (is_wp_error($user_id)) {
            // Handle error if user creation fails
            echo "Error creating user: " . $user_id->get_error_message();
        } else {
            update_user_meta($user_id, 'competicao_id', $competicao_id);
            if (isset($_POST['moment_id'])) {
                foreach ($_POST['moment_id'] as $moment_index => $moment_id) {
                    global $wpdb;
                    $table_name = TABLE_JURIS_MOMENTO;
                    if (isset($_POST['groupo'])){
                        $groupo = $_POST['groupo'];

                        if(isset($groupo[$moment_index])){
                            $equipa_id = $_POST['equipa_id'];
                            foreach ($groupo[$moment_index] as $groupo_index => $groupo_id) {
                                $equipa_id =  get_groupo_equipa_id($groupo_id);
                                $arr = [
                                    'momento_id' => $moment_id[0],
                                    'groupo_id' => $groupo_id,
                                    'equipa_id' => $equipa_id,
                                    'user_id' => $user_id
                                ];
                                $wpdb->insert("$table_name", $arr);
                                $equipas_id = $wpdb->insert_id;
                            }
                        }
                    }

                }


            }
			
			
			if (trim($email) != '') {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $sender_name = 'Jap';
                        $subject = "Sua nova conta foi criada no JAP como juri";
                        $to = $email;
//                         add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
                        $headers = array(
                            'Content-Type: text/html; charset=UTF-8',
                            'From: ' . $sender_name . ' <info@japortugal.org>',
                            'Reply-To: info@japortugal.org' 
                        );


                        $email_message = '
    <img src="'.home_url().'/wp-content/uploads/2024/03/logo-jap.png" height="25px"/>
    <p style="margin-bottom: 5px;margin-top:10px;">Sua nova conta foi criada</p>
 <p style="margin-bottom: 5px">User Login: '.$email.' </p>
 <p style="margin-bottom: 5px">User Password: '.$password.'</p>
 <p style="margin-bottom: 5px"><a href="'.home_url().'">Click here to login</a></p>
';



// Send email
                        $sent = wp_mail($to, $subject, $email_message, $headers);

// Remove content type filter
//                         remove_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');

                    }

                }
        }
    }
}
