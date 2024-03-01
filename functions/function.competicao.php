<?php
/**
 * Get JAP competicao View
 */
function jap_competicao_callback()
{
    global $wpdb;
    $table_name = TABLE_COMPETICAO;
    $competicao_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC", ""), ARRAY_A);
    $data['competicao_list'] = $competicao_list;
    view('admin.competicao.list', $data);
}

/**
 *  JAP competicao delete
 */
function jap_delete_competicao()
{
    global $wpdb;
    $table_name = TABLE_COMPETICAO;
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    $wpdb->delete(TABLE_MOMENTO, array('competicao_id' => $id));
    $row_exists = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

    if (count($row_exists) > 0) {
        $result = $wpdb->delete("$table_name", array('id' => $id));

        if ($result === false) {
            // There was an error in the delete operation
            $error_message = $wpdb->last_error;
            // Handle the error as needed (e.g., log it, display a message)
            // For demonstration purposes, let's just echo the error
            echo "Error deleting row: $error_message";
        } else {
            // Successfully deleted, redirect
            jap_redirect('competicao');
        }
    } else {
        // Row does not exist, handle accordingly (e.g., show a message)
        // For demonstration purposes, let's just echo a message
        echo "Row with ID $id does not exist.";
    }
}

/**
 * Get JAP add competicao View
 */
function jap_competicao_add_callback()
{
    view('admin.competicao.add');
}

/**
 * Edit page of competicao for admin
 */
function jap_edit_competicao()
{
    if (!empty($_GET["competicao_id"])) {
        $competicao_id = $_GET["competicao_id"];
        $competicao = get_competicao($competicao_id);
        if (!$competicao) {
            jap_redirect('competicaos');
        }

    } else {
        jap_redirect('competicao');
    }
    $competicao_id = $competicao->id;
    $momentos = get_momento($competicao_id);

    $data['competicao'] = $competicao;
    $data['momentos_list'] = $momentos;
    view('admin.competicao.edit', $data);
}

function get_competicao($competicao_id)
{
    $competicao = [];
    global $wpdb;
    $table_name = TABLE_COMPETICAO;
    $sql = "SELECT * FROM " . $table_name . " where id = " . $competicao_id;
    $request_data = $wpdb->get_results($sql);
    if (isset($request_data)) {
        foreach ($request_data as $data) {
            $competicao = $data;
            break;
        }
    }
    return $competicao;
}


function get_momento($competicao_id)
{
    global $wpdb;
    $table_momento = TABLE_MOMENTO;

    $sql = $wpdb->prepare("SELECT * FROM $table_momento WHERE competicao_id = %d", $competicao_id);

    $request_data = $wpdb->get_results($sql);

    return $request_data;
}

function get_observation($moment_id)
{
    global $wpdb;
    $table_momento = TABLE_MOMENTO_META;

    $sql = $wpdb->prepare("SELECT * FROM $table_momento WHERE moments_id = %d", $moment_id);

    $request_data = $wpdb->get_results($sql);

    return $request_data;
}

// functions.php or your custom plugin file

// Callback function for deleting the row
function delete_momento_meta_row()
{
    // Check if the user has the necessary permissions
    if (current_user_can('manage_options')) {
        // Get the observation_id from the AJAX request
        $observation_id = isset($_POST['observation_id']) ? sanitize_text_field($_POST['observation_id']) : '';

        // Perform the row deletion logic (replace with your actual deletion code)
        if ($observation_id) {
            global $wpdb;
            $table_name = TABLE_MOMENTO_META;

            $wpdb->delete(
                $table_name,
                array('id' => $observation_id),
                array('%d')
            );

            // Send a success response
            wp_send_json_success();
        }
    }

    // Send an error response if the user doesn't have permission or if the request is invalid
    wp_send_json_error();
}

// Hook the callback function to the custom AJAX action
add_action('wp_ajax_delete_momento_meta_row', 'delete_momento_meta_row');


function delete_momento_row()
{
    // Check if the user has the necessary permissions
    if (current_user_can('manage_options')) {
        global $wpdb;
        // Get the observation_id from the AJAX request
        $moment_id = isset($_POST['moment_id']) ? sanitize_text_field($_POST['moment_id']) : '';

        $wpdb->delete(TABLE_MOMENTO_META, array('moments_id' => $moment_id));
        $wpdb->delete(TABLE_MOMENTO, array('id' => $moment_id));


        // Send a success response
        wp_send_json_success();

    }

    // Send an error response if the user doesn't have permission or if the request is invalid
    wp_send_json_error();
}

// Hook the callback function to the custom AJAX action
add_action('wp_ajax_delete_momento_row', 'delete_momento_row');



