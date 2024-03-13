<?php
/**
 * Get JAP 
 */
function jap_equipas_callback()
{
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $equipa_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC", ""), ARRAY_A);
    $data['equipa_list'] = $equipa_list;
    view('admin.equipas.list', $data);
}
function jap_delete_equipa_competicao(){
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $id = isset($_REQUEST['equipa_id']) ? intval($_REQUEST['equipa_id']) : "";
    $wpdb->delete(TABLE_GROUPO , array('equipas_id' => $id));
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
            jap_redirect('equipas');
        }
    } else {
        // Row does not exist, handle accordingly (e.g., show a message)
        // For demonstration purposes, let's just echo a message
        echo "Row with ID $id does not exist.";
    }
}

function jap_edit_equipa_competicao()
{
    if (!empty($_GET["equipa_id"])) {
        $equipa_id = $_GET["equipa_id"];
        $equipa = get_equipa_info($equipa_id);
        if (!$equipa) {
            jap_redirect('equipas');
        }

        $data['equipa'] = $equipa;
        view('admin.equipas.edit', $data);
    } else {
        jap_redirect('equipas');
    }
}

function get_equipa_info($equipa_id)
{
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $equipa = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $equipa_id));
    return $equipa;
}


/**
 * Get JAP Chart View
 */
function jap_equipas_add_callback()
{

    $data['competicao_list'] = get_competicao_list();
    $data['equipa_nome_list'] = get_all_equipas_nome();

    view('admin.equipas.add', $data);
}

function get_competicao_list()
{
    global $wpdb;
    $table_name = TABLE_COMPETICAO;
    $competicao_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC", ""), ARRAY_A);
    return $competicao_list;
}

function get_all_equipas_nome()
{
    global $wpdb;
    $table_name = TABLE_EQUIPAS;
    $equipa_list = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name  ORDER BY id DESC", ""), ARRAY_A);
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
    $equipa_id = isset($_POST['equipa_id']) ? intval($_POST['equipa_id']) : 0;


    // Query the database to fetch momento table data based on competicao_id
    $table_name_momento = TABLE_MOMENTO;
    $query = $wpdb->prepare("SELECT * FROM $table_name_momento WHERE competicao_id = %d", $competicao_id);
    $momento_data = $wpdb->get_results($query);

    // Output the fetched data (you may format this output based on your needs)
    if (!empty($momento_data)) {
        foreach ($momento_data as $momento) { ?>
            <input type="hidden" name="moment_id[]" value="<?php echo $momento->id; ?>">
            <label for="<?php echo sanitize_title($momento->title) ?>"><?php echo $momento->title; ?></label><br>
            <select name="groupo[]" id="<?php echo sanitize_title($momento->title) ?>" required>
                <option value="">Select Group</option>
                <?php $groupo_list = get_equipa_select_groupo($equipa_id);
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



// Callback function for fetching momento table data
function fetch_momento_compe_data()
{
    global $wpdb;


    // Get the competicao_id from the AJAX request
    $competicao_id = isset($_POST['competicao_id']) ? intval($_POST['competicao_id']) : 0;


    // Query the database to fetch momento table data based on competicao_id
    $table_name_momento = TABLE_MOMENTO;
    $query = $wpdb->prepare("SELECT * FROM $table_name_momento WHERE competicao_id = %d", $competicao_id);
    $momento_data = $wpdb->get_results($query);

    // Output the fetched data (you may format this output based on your needs)
    if (!empty($momento_data)) {?>
    <br>
    <label for="moment_id">Momento</label>
    <select name="moment_id" id="moment_id" required>
    <?php
        foreach ($momento_data as $momento) { ?>
        <option value="<?php echo $momento->id; ?>"><?php echo $momento->title; ?></option>
        <?php }
    } else {
        echo '<p>No data found for the selected competicao_id</p>';
    }

    // Don't forget to exit after outputting the data
    wp_die();
}

// Hook the callback function to the custom AJAX action
add_action('wp_ajax_fetch_momento_compe_data', 'fetch_momento_compe_data');
add_action('wp_ajax_nopriv_fetch_momento_compe_data', 'fetch_momento_compe_data');


function get_equipa_select_groupo($equipa_id)
{
    global $wpdb;
    $table_name = TABLE_GROUPO;
    $groupo_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name WHERE equipas_id =" . $equipa_id, ""));
    return $groupo_list;
}

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
    $groupo_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name WHERE equipas_id=" . $equipa_id, ""));
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


function check_equipa_groupo($groupo_id, $momento_id, $equipa_id, $equipa_user_id)
{
    global $wpdb;

    $table_name = TABLE_EQUIPAS_MOMENTO;

    $is_found = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE equipas_id = %d AND moments_id = %d AND groupo_id = %d AND user_id = %d", $equipa_id, $momento_id, $groupo_id, $equipa_user_id));


    if ($is_found > 0) {
        echo 'selected="selected"';
    } else {
        echo "$groupo_id,$momento_id,$equipa_id,$equipa_user_id";
    }

}

function get_equipa_competicao($competicao_id)
{
    if ($competicao_id) {
        global $wpdb;

        // Assuming 'competicao' is the table name
        $competicao_table = TABLE_COMPETICAO;

        // Get the name column for the given competicao_id
        $competicao_name = $wpdb->get_var($wpdb->prepare("SELECT nome FROM $competicao_table WHERE id = %d", $competicao_id));
        return $competicao_name;
    } else {
        return 'Concorrência não encontrada';
    }
}
function get_equipa_momentos($equipe_id)
{
    if ($equipe_id) {
        global $wpdb;

        // Assuming 'competicao' is the table name
        $equipe_table = TABLE_EQUIPAS_MOMENTO;
        $momento_table = TABLE_MOMENTO;
        $groupo_table = TABLE_GROUPO;
        $sql = "SELECT 
        wm.title AS moment_title,
        wg.nome AS group_name
    FROM 
        $equipe_table AS em
    INNER JOIN 
        $momento_table AS wm ON em.moments_id = wm.id
    INNER JOIN 
        $groupo_table AS wg ON em.groupo_id = wg.id
    WHERE 
        em.equipas_id = $equipe_id";

        $query = $wpdb->prepare($sql);

        $results = $wpdb->get_results($query);
       if(!empty($results)){
           foreach ($results as $item_result) {
               echo '<p><strong>'.$item_result->moment_title.'</strong> : <span>'.$item_result->group_name.'</span></p>';
           }
       }
    } else {
        return '';
    }
}

function get_moment_meta($momento_item_id)
{
    if ($momento_item_id) {
        global $wpdb;
        // Assuming 'competicao' is the table name
        $moment_meta_table = TABLE_MOMENTO_META;
        // Get the name column for the given competicao_id
        $moment_meta = $wpdb->get_results($wpdb->prepare("SELECT * FROM $moment_meta_table WHERE moments_id = %d", $momento_item_id));
        return $moment_meta;
    }
}

function get_all_equipa_list($momento_item_id)
{
    if ($momento_item_id) {
        global $wpdb;
        // Assuming 'competicao' is the table name
        $moment_meta_table = TABLE_JURIS_MOMENTO;
        // Get the name column for the given competicao_id
        $user_id = get_current_user_id();
        $sql = "SELECT * FROM $moment_meta_table WHERE momento_id = $momento_item_id AND user_id = $user_id";

        $moment_meta = $wpdb->get_results($wpdb->prepare($sql));
        return $moment_meta;
    }
}