<?php
/**
 * Get JAP Chart View
 */
function jap_juris_callback(){
    global $wpdb;
    $role = 'author';

// Join the user and usermeta tables to filter by role
    $query = $wpdb->prepare("
    SELECT u.*
    FROM {$wpdb->users} AS u
    INNER JOIN {$wpdb->usermeta} AS um ON u.ID = um.user_id
    WHERE um.meta_key = '{$wpdb->prefix}capabilities' 
    AND um.meta_value LIKE %s
    ORDER BY u.ID DESC
", '%' . $wpdb->esc_like('"' . $role . '"') . '%');

    $juris_list = $wpdb->get_results($query, ARRAY_A);
    $data['juris_list'] = $juris_list;
    view('admin.juris.list', $data);

}

/**
 * Get JAP Chart View
 */
function jap_juris_add_callback(){
    global $wpdb;
    $table_name = TABLE_COMPETICAO;
    $competicao_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC", ""), ARRAY_A);
    $data['competicao_list'] = $competicao_list;
    view('admin.juris.add',$data);
}

// Callback function for fetching momento table data
function fetch_juris_momento_data()
{
    global $wpdb;


    // Get the competicao_id from the AJAX request
    $competicao_id = isset($_POST['competicao_id']) ? intval($_POST['competicao_id']) : 0;


    // Query the database to fetch momento table data based on competicao_id
    $table_name_momento = TABLE_MOMENTO;
    $query = $wpdb->prepare("SELECT * FROM $table_name_momento WHERE competicao_id = %d", $competicao_id);
    $momento_data = $wpdb->get_results($query);

    // Output the fetched data (you may format this output based on your needs)
    if (!empty($momento_data)) { ?>
        <h2>
            O que vao avaliar
        </h2>
        <?php
        foreach ($momento_data as $momento_index =>$momento) { ?>
            <div class="equipa_groupo_wrapper">
                <div class="select__all__checkbox">
                    <input type="checkbox" id="checkbox__<?php echo sanitize_title($momento->title); ?>">
                    <span>Select all</span>
                </div>
                <h3 class="momento_title"><?php echo $momento->title; ?></h3>
                <input type="hidden" name="moment_id[<?php echo $momento_index; ?>][]" value="<?php echo $momento->id; ?>">
                <div class="equipa_wrapper">
                    <?php
                    $equipa_list = get_equipas_all();
                    foreach ($equipa_list as  $equipa_item) { ?>
                        <div style="display: inline-block;margin-right:15px;">
                            <h4><?php echo $equipa_item->nome;?></h4>

                            <?php
                            $groupo_list = get_equipa_groupo($equipa_item->id);
                            foreach ($groupo_list as $index => $groupo_item) { ?>
                                <input type="hidden" name="equipa_id[<?php echo $momento_index; ?>][]" value="<?php echo $equipa_item->id; ?>">
                                <input type="checkbox" id="<?php echo sanitize_title($groupo_item->nome).$index?>" name="groupo[<?php echo $momento_index; ?>][]" value="<?php echo $groupo_item->id; ?>">
                                <label for="<?php echo sanitize_title($groupo_item->nome).$index?>"><?php echo $groupo_item->nome; ?></label><br>
                            <?php } ?>
                        </div>

                    <?php }
                    ?>
                </div>
            </div>



        <?php }
    } else {
        echo '<p>No data found for the selected competicao_id</p>';
    }

    // Don't forget to exit after outputting the data
    wp_die();
}

// Hook the callback function to the custom AJAX action
add_action('wp_ajax_fetch_juris_momento_data', 'fetch_juris_momento_data');
add_action('wp_ajax_nopriv_fetch_juris_momento_data', 'fetch_juris_momento_data');

function get_juri_competicao($user_id){
    // Get the competicao_id from user meta
    $competicao_id = get_user_meta($user_id, 'competicao_id', true);

    if ($competicao_id) {
        global $wpdb;

        // Assuming 'competicao' is the table name
        $competicao_table = TABLE_COMPETICAO;

        // Get the name column for the given competicao_id
        $competicao_name = $wpdb->get_var($wpdb->prepare("SELECT nome FROM $competicao_table WHERE id = %d", $competicao_id));
        return $competicao_name;
    } else {
        return 'Competicao not found for this user';
    }
}

function jap_delete_juris()
{
    // Check if the user has the necessary permissions
    if (current_user_can('manage_options')) {
        global $wpdb;
        // Get the observation_id from the AJAX request
        $user_id = isset($_REQUEST['id']) ? sanitize_text_field($_REQUEST['id']) : '';

        global $wpdb;
        $wpdb->delete(TABLE_JURIS_MOMENTO, array('user_id' => $user_id));
        $wpdb->delete(TABLE_USERS, array('id' => $user_id));
        jap_redirect('juris');

    }
}

/**
 * Edit page of competicao for admin
 */
function jap_edit_competicao()
{
    if (!empty($_GET["juri_id"])) {
        $juri_id = $_GET["juri_id"];
        $juri = get_user_info($juri_id);
        if (!$juri) {
            jap_redirect('juris');
        }
        $data['juri'] = $juri;
        view('admin.juris.edit', $data);
    } else {
        jap_redirect('juris');
    }


}

function get_user_info($user_id){
    global $wpdb;
    $table_name = TABLE_USERS;
    $user = $wpdb->get_var($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $user_id));
    return $user;
}