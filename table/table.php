<?php
/** Table names */
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
global $wpdb;


$mvt_db_version = get_option('jap_db_version');
$charset_collate = $wpdb->get_charset_collate();

$table_name = TABLE_COMPETICAO;
if (empty($mvt_db_version)) {
    /***************** competicao *********************/
    $query = "
            CREATE TABLE $table_name(
                id bigint(11) NOT NULL AUTO_INCREMENT,
                nome varchar(50) NOT NULL,
                image varchar(250) NOT NULL,
                status varchar(250) NOT NULL,
                user_id bigint(11) NOT NULL,
                published_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                update_date datetime DEFAULT NULL,
                PRIMARY KEY id(id)
            )$charset_collate;";

    dbDelta($query);

    /***************** momentos *********************/
    $table_name =TABLE_MOMENTO;
    $competicao_table_name = TABLE_COMPETICAO; // Replace with the actual table name
    $query = "
            CREATE TABLE $table_name (
                id bigint(11) NOT NULL AUTO_INCREMENT,
                competicao_id bigint(11) NOT NULL,
                title varchar(255) NOT NULL,
                user_id bigint(11) NOT NULL,
                PRIMARY KEY (id),
                FOREIGN KEY (competicao_id) REFERENCES $competicao_table_name(id) 
                ON DELETE CASCADE
            )$charset_collate;";

    dbDelta($query);

    /***************** momento_meta *********************/
    $table_name = TABLE_MOMENTO_META;
    $momento_table_name = TABLE_MOMENTO; // Replace with the actual table name
    $query = "
            CREATE TABLE $table_name (
                id bigint(11) NOT NULL AUTO_INCREMENT,
                moments_id bigint(11) NOT NULL,
                title varchar(255) NOT NULL,
                peso_da_nota decimal(10,2) NOT NULL,
                o_que_avaliamos text NOT NULL,
                user_id bigint(11) NOT NULL,
                PRIMARY KEY (id),
                FOREIGN KEY (moments_id) REFERENCES $momento_table_name(id) ON DELETE CASCADE
            )$charset_collate;";

    dbDelta($query);


    /***************** TABLE_EQUIPAS *********************/
    $table_name_equipas = TABLE_EQUIPAS;
    $competicao_table_name = TABLE_COMPETICAO; // Replace with the actual table name

    $query_equipas = "
    CREATE TABLE $table_name_equipas (
        id bigint(11) NOT NULL AUTO_INCREMENT,
        nome varchar(255) NOT NULL,
        competicao_id bigint(11),
        user_id bigint(11) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (competicao_id) REFERENCES $competicao_table_name(id) ON DELETE CASCADE
    )$charset_collate;";

    dbDelta($query_equipas);

    /***************** grupo *********************/
    $table_name = TABLE_GROUPO;
    $equipas_table_name = TABLE_EQUIPAS; // Replace with the actual table name

    $query = "
    CREATE TABLE $table_name (
        id bigint(11) NOT NULL AUTO_INCREMENT,
        equipas_id bigint(11) NOT NULL,
        nome varchar(255) NOT NULL,
        user_id bigint(11) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (equipas_id) REFERENCES $equipas_table_name(id) ON DELETE CASCADE
    )$charset_collate;";

    dbDelta($query);

    /***************** equipas_momento *********************/
    $table_name = TABLE_EQUIPAS_MOMENTO;
    $equipas_table_name = TABLE_EQUIPAS; // Replace with the actual table name
    $momento_table_name = TABLE_MOMENTO; // Replace with the actual table name
    $group_table_name = TABLE_GROUPO; // Replace with the actual table name

    $query = "
    CREATE TABLE $table_name (
        id bigint(11) NOT NULL AUTO_INCREMENT,
        equipas_id bigint(11) NOT NULL,
        moments_id bigint(11) NOT NULL,
        groupo_id bigint(11) NOT NULL,
        user_id bigint(11) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (equipas_id) REFERENCES $equipas_table_name(id) ON DELETE CASCADE,
    FOREIGN KEY (moments_id) REFERENCES $momento_table_name(id) ON DELETE CASCADE,
    FOREIGN KEY (groupo_id) REFERENCES $group_table_name(id) ON DELETE CASCADE
    )$charset_collate;";

    dbDelta($query);

    /***************** juris_equipas_rank *********************/
    $table_name = TABLE_JURIS_EQUIPAS_RANK;
    $users_table_name = TABLE_USERS; // Replace with the actual user table name
    $momento_table_name = TABLE_MOMENTO; // Replace with the actual table name
    $equipas_table_name = TABLE_EQUIPAS; // Replace with the actual table name

// Define the collation
    $charset_collate = $wpdb->get_charset_collate();

    $query = "
CREATE TABLE $table_name (
    id bigint(11) NOT NULL AUTO_INCREMENT,
    momento_id bigint(11) NOT NULL,
    equipa_id bigint(11) NOT NULL,
    equipa_rank int NOT NULL,
    user_id bigint(11) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (momento_id) REFERENCES $momento_table_name(id) ON DELETE CASCADE,
    FOREIGN KEY (equipa_id) REFERENCES $equipas_table_name(id) ON DELETE CASCADE
) $charset_collate;";

    dbDelta($query);
    if ($wpdb->last_error !== '') {
        die('MySQL Error: ' . $wpdb->last_error);
    }


    /***************** juris_momento *********************/
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_name = TABLE_JURIS_MOMENTO;
    $users_table_name = TABLE_USERS; // Replace with the actual user table name
    $momento_table_name = TABLE_MOMENTO; // Replace with the actual table name
    $group_table_name = TABLE_GROUPO; // Replace with the actual table name
    $equipas_table_name = TABLE_EQUIPAS; // Replace with the actual table name

    $query = "
    CREATE TABLE $table_name (
        id bigint(11) NOT NULL AUTO_INCREMENT,
        momento_id bigint(11) NOT NULL,
        groupo_id bigint(11) NOT NULL,
        equipa_id bigint(11) NOT NULL,
        user_id bigint(11) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (momento_id) REFERENCES $momento_table_name(id) ON DELETE CASCADE,
        FOREIGN KEY (groupo_id) REFERENCES $group_table_name(id) ON DELETE CASCADE,
        FOREIGN KEY (equipa_id) REFERENCES $equipas_table_name(id) ON DELETE CASCADE
    )$charset_collate;";

    dbDelta($query);

    $mvt_db_version = '1.1';
    add_option('jap_db_version', $mvt_db_version);
}