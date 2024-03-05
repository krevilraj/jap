<?php
/**
 * Get JAP Chart View
 */
function jap_equipas_callback(){
    view('admin.equipas.list');
}



/**
 * Get JAP Chart View
 */
function jap_equipas_add_callback(){
    global $wpdb;
    $table_name = TABLE_COMPETICAO;
    $competicao_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC", ""), ARRAY_A);
    $data['competicao_list'] = $competicao_list;
    $data['equipa_nome_list'] = get_all_equipas_nome();

    view('admin.equipas.add',$data);
}

function get_all_equipas_nome(){
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $equipa_list = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE competicao_id IS NULL ORDER BY id DESC", ""), ARRAY_A);
    return $equipa_list;
}

/**
 * Get JAP Groupo View
 */
function jap_groupo_callback()
{

    $data['equipas_list'] = get_all_equipa();
    view('admin.equipas.groupo', $data);
}

function get_groupo($id)
{
    global $wpdb;
    $table_name = TABLE_GROUPO;
    $groupo_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name WHERE equipas_id = " . $id . " ORDER BY id DESC", ""));
    return $groupo_list;
}

function jap_delete_equipa()
{
    if (current_user_can('manage_options')) {
        global $wpdb;
        // Get the observation_id from the AJAX request
        $equipa_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
        $wpdb->delete(TABLE_GROUPO, array('equipas_id' => $equipa_id));
        $wpdb->delete(TABLE_EQUIPAS, array('id' => $equipa_id));
        jap_redirect('groupo');

    }
}

function get_all_equipa()
{
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $equipas_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC"));
    return $equipas_list;
}


/**
 * Edit page of groupo for admin
 */
function jap_edit_equipa_groupo()
{

    if (!empty($_GET["equipa_id"])) {
        $equipa_id = $_GET["equipa_id"];
        $equipa = get_equipas($equipa_id);
        if (!$equipa) {
            jap_redirect('groupo');
        }

    } else {
        jap_redirect('groupo');
    }
    $equipa_id = $equipa->id;
    $groupo = get_equipas_detail($equipa_id);


    $data['equipa'] = $equipa;
    $data['groupo'] = $groupo;
    view('admin.equipas.groupo_equipa_edit', $data);
}

function get_equipas($equipas_id)
{
    $equipa = [];
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $sql = "SELECT * FROM " . $table_name . " where id = " . $equipas_id;
    $request_data = $wpdb->get_results($sql);
    if (isset($request_data)) {
        foreach ($request_data as $data) {
            $equipa = $data;
            break;
        }
    }
    return $equipa;
}

function get_equipas_detail($equipas_id)
{
    global $wpdb;
    $table_momento = TABLE_GROUPO;

    $sql = $wpdb->prepare("SELECT * FROM $table_momento WHERE equipas_id = %d", $equipas_id);

    $request_data = $wpdb->get_results($sql);

    return $request_data;
}


// Callback function for fetching momento table data
function fetch_momento_data()
{
    global $wpdb;


    // Get the competicao_id from the AJAX request
    $competicao_id = isset($_POST['competicao_id']) ? intval($_POST['competicao_id']) : 0;


    // Query the database to fetch momento table data based on competicao_id
    $table_name_momento = TABLE_MOMENTO;
    $query = $wpdb->prepare("SELECT * FROM $table_name_momento WHERE competicao_id = %d", $competicao_id);
    $momento_data = $wpdb->get_results($query);

    // Output the fetched data (you may format this output based on your needs)
    if (!empty($momento_data)) {
        foreach ($momento_data as $momento) { ?>
            <input type="hidden" name="moment_id[]" value="<?php echo $momento->id; ?>">
            <label for="<?php echo sanitize_title($momento->title)?>"><?php echo $momento->title; ?></label><br>
            <select name="groupo[]" id="<?php echo sanitize_title($momento->title)?>">
                <option value="">Select Group</option>
                <?php $groupo_list = get_all_groupo();
                foreach ($groupo_list as $groupo) { ?>
                    <option value="<?php echo $groupo->id; ?>"><?php echo $groupo->nome; ?></option>
                <?php } ?>


            </select><br><br>

        <?php }
    } else {
        echo '<p>No data found for the selected competicao_id</p>';
    }

    // Don't forget to exit after outputting the data
    wp_die();
}

// Hook the callback function to the custom AJAX action
add_action('wp_ajax_fetch_momento_data', 'fetch_momento_data');
add_action('wp_ajax_nopriv_fetch_momento_data', 'fetch_momento_data');




function get_all_groupo()
{
    global $wpdb;
    $table_name = TABLE_GROUPO;
    $groupo_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name", ""));
    return $groupo_list;
}
function get_equipa_groupo($equipa_id)
{
    global $wpdb;
    $table_name = TABLE_GROUPO;
    $groupo_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name WHERE equipas_id=".$equipa_id, ""));
    return $groupo_list;
}

function get_equipas_all()
{
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $equipa_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name", ""));
    return $equipa_list;
}

function delete_groupo_row()
{
    // Check if the user has the necessary permissions
    if (current_user_can('manage_options')) {
        // Get the observation_id from the AJAX request
        $groupo_id = isset($_POST['groupo_id']) ? sanitize_text_field($_POST['groupo_id']) : '';

        // Perform the row deletion logic (replace with your actual deletion code)
        if ($groupo_id) {
            global $wpdb;
            $table_name = TABLE_GROUPO;

            $wpdb->delete(
                $table_name,
                array('id' => $groupo_id),
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
add_action('wp_ajax_delete_groupo_row', 'delete_groupo_row');