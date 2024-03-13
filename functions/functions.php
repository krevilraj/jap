<?php
require_once(JAP_PATH . 'functions/function.competicao.php');
require_once(JAP_PATH . 'functions/function.equipas.php');
require_once(JAP_PATH . 'functions/function.juris.php');


/**
 * Get JAP Chart View
 */
function jap_avaliacoes_callback()
{
    view('admin.avaliacoes.list');
}

/**
 * Get JAP Chart View
 */
function jap_avaliacoes_add_callback()
{
    view('admin.avaliacoes.add');
}

add_action('wp_ajax_check_ajax_request', 'check_ajax_request_callback');
add_action('wp_ajax_nopriv_check_ajax_request', 'check_ajax_request_callback');

function check_ajax_request_callback()
{
    if (isset($_POST['equipa_id'], $_POST['momento_id'], $_POST['momento_meta_id'], $_POST['user_id'], $_POST['rank_value'])) {
        $equipa_id = sanitize_text_field($_POST['equipa_id']);
        $momento_id = sanitize_text_field($_POST['momento_id']);
        $momento_meta_id = sanitize_text_field($_POST['momento_meta_id']);
        $user_id = sanitize_text_field($_POST['user_id']);
        $rank_value = sanitize_text_field($_POST['rank_value']);

        $existing_entry = get_juris_rank($equipa_id, $momento_id, $momento_meta_id);
        global $wpdb;
        $table_name = TABLE_JURIS_EQUIPAS_RANK;
        if (!$existing_entry) {
            // Insert new entry
            $wpdb->insert($table_name, array(
                'equipa_id' => $equipa_id,
                'momento_id' => $momento_id,
                'momento_meta_id' => $momento_meta_id,
                'user_id' => $user_id,
                'equipa_rank' => $rank_value,
            ));
            echo 'Inserted successfully!';
        } else {
            // Update existing entry
            $wpdb->update($table_name, array('equipa_rank' => $rank_value), array('id' => $existing_entry->id));
            echo 'Updated successfully!';
        }

        wp_die(); // Always die in functions echoing AJAX content.
    }
}

function get_juris_rank($equipa_id, $moment_id, $moment_meta_id)
{
    // Check if the entry already exists
    global $wpdb;
    $table_name = TABLE_JURIS_EQUIPAS_RANK;
    $rank = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE equipa_id = %d AND momento_id = %d AND user_id = %d AND momento_meta_id = %d",
        $equipa_id,
        $moment_id,
        get_current_user_id(),
        $moment_meta_id
    ));
    return $rank;
}

function get_momento_meta($moment_id)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'momento_meta';

    // Perform the SQL query
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id FROM $table_name WHERE moments_id = %d",
            $moment_id
        ),
        ARRAY_A
    );

    // Extract the 'id' values from the results
    $id_array = wp_list_pluck($results, 'id');

    return $id_array;
}

function get_momento_meta_per($moment_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'momento_meta';
    // Perform the SQL query
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT peso_da_nota FROM $table_name WHERE moments_id = %d",
            $moment_id
        ),
        ARRAY_A
    );
    $id_array = wp_list_pluck($results, 'peso_da_nota');
    return $id_array;
}


function calculate_score($juri_id, $moment_id, $moment_meta_id_arr, $moment_meta_id_per_arr, $equipa_id)
{
    if (!empty($moment_meta_id_arr)) {
        $total_score = 0;
        $count_moment_meta = count($moment_meta_id_per_arr);
        foreach ($moment_meta_id_arr as $moment_meta_index => $moment_meta_id) {
            global $wpdb;
            $table_name = TABLE_JURIS_EQUIPAS_RANK;
            $rank = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE equipa_id = %d AND momento_id = %d AND user_id = %d AND momento_meta_id = %d",
                $equipa_id,
                $moment_id,
                $juri_id,
                $moment_meta_id
            ));

            if ($rank) {
                $percentValue = $moment_meta_id_per_arr[$moment_meta_index];
                if ($percentValue) {
                    $total_score += $percentValue / 100 * $rank->equipa_rank;
                }

            }


        }
       // $total_score = $total_score / $count_moment_meta;
        $formatted_total = number_format($total_score, 2);
        return $formatted_total;
    }
    return '';

}

