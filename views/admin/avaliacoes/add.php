<style>
    .equipa_wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .equipa_item {
        width: 323px;
        margin-right: 10px;
    }

    .equipa_item table {
        width: 100%;
    }

    .equipa_item h2:before {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 5px;
        width: 55px;
        background-color: #111;
    }

    .equipa_item h2:after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 2px;
        height: 1px;
        width: 95%;
        max-width: 255px;
        background-color: #333;
    }

    .equipa_item h2 {
        position: relative;
        padding-bottom: 15px;
    }

    .equipa_item h3 {
        color: #286075;
    }

    .equipa_title {
        border-bottom: 1px solid #ccc;
        padding-bottom: 20px
    }
</style>
<section class="jap_admin_list">
    <div>
        <h1>Lista de Avaliação</h1>
    </div>
    <div class="table__wrapper">
        <div class="jap_filter">
            <form action="/wp-admin/admin.php">
                <label for="competicao_id">Competição</label>
                <select name="competicao_id" id="competicao_id">
					<option>Selecione Competição</option>
                    <?php
                    $competicao_id = '';
                    $competicao_list = get_competicao_list();
//                     if (!isset($_GET['competicao_id'])) {
//                         if (!empty($competicao_list)) {
//                             $competicao_id = $competicao_list[0]['id'];
//                             echo $competicao_id;
//                         }

//                     } else {
//                         $competicao_id = $_GET['competicao_id'];

//                     }

                    if (isset($competicao_list)) {
                        foreach ($competicao_list as $index => $data) { ?>
                            <option value="<?php echo $data['id']; ?>" <?php if ($competicao_id == $data['id']) echo 'selected="selected"' ?> ><?php echo $data['nome']; ?></option>
                            <?php
                        }
                    } ?>
                    <input type="hidden" name="page" value="add-avaliacoes">
                </select>
                <div id="momento_data">
                    <?php
                    $competicao_id = isset($_GET['competicao_id']) ? intval($_GET['competicao_id']) : 0;
                    $moment_id = isset($_GET['moment_id']) ? intval($_GET['moment_id']) : 0;

                    $moment_meta_id_arr = get_momento_meta($moment_id);
                    $moment_meta_id_per_arr = get_momento_meta_per($moment_id);

                    global $wpdb;
                    // Query the database to fetch momento table data based on competicao_id
                    $table_name_momento = TABLE_MOMENTO;
                    $query = $wpdb->prepare("SELECT * FROM $table_name_momento WHERE competicao_id = %d", $competicao_id);
                    $momento_data = $wpdb->get_results($query);

                    // Output the fetched data (you may format this output based on your needs)
                    if (!empty($momento_data)) { ?>
                        <br>
                        <label for="moment_id">Momento</label>
                        <select name="moment_id" id="moment_id" required>
                            <?php
                            foreach ($momento_data as $momento) { ?>
                                <option value="<?php echo $momento->id; ?>" <?php if ($momento->id == $moment_id) echo 'selected="selected"' ?>><?php echo $momento->title; ?></option>
                                <?php
                            }


                            ?>
                        </select>
                    <?php } ?>
                </div>
                <button type="submit" class="submit__btn button button-primary">Submit</button>
            </form>

            <?php
            wp_enqueue_script('jap_datatables_js');
            wp_enqueue_style('jap_datatables_css');
			  wp_enqueue_style('kia__request_datatables_css');
 wp_enqueue_script('kia__request_datatables_jszip');
 wp_enqueue_script('kia__request_datatables_btn_js');
 wp_enqueue_script('kia__request_datatables_html5js');
 wp_enqueue_script('kia__request_datatables_printjs');
            ?>
            <section class="jap_admin_list">
                <div>
                    <h1>Dados de avaliação</h1>

                </div>
                <div class="table__wrapper">
                    <table border="1" cellpadding="10" id="request-list" class="request-list">
                        <thead>
                        <tr>
                            <th></th>
                            <?php
                            $table_equipas_rank = TABLE_JURIS_EQUIPAS_RANK;
                            $table_juris = TABLE_USERS;
                            $sql = "
                                SELECT DISTINCT e.user_id, user_login
                                FROM $table_equipas_rank e
                                JOIN $table_juris r ON e.user_id = r.id
                                WHERE e.momento_id = $moment_id;
                            ";
                            $query = $wpdb->prepare($sql);
                            $results = $wpdb->get_results($query);
                            foreach ($results as $user_info) {
                                $user_id = $user_info->user_id;
                                $juri[] = $user_id;
                                ?>
                                <th><?php echo $user_info->user_login; ?></th>
                            <?php } ?>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        global $wpdb;
                        $table_equipas = TABLE_EQUIPAS;
                        $table_juris = TABLE_JURIS_EQUIPAS_RANK;
                        $table_momento = TABLE_MOMENTO;
                        $query = $wpdb->prepare("
    SELECT DISTINCT e.id, e.nome
    FROM $table_equipas e
    JOIN $table_juris r ON e.id = r.equipa_id
    JOIN $table_momento m ON r.momento_id = m.id
    WHERE m.competicao_id = %d
", $competicao_id);

                        $results = $wpdb->get_results($query);

                        foreach ($results as $equipa) {
                            $all_juri_score = [];
                            ?>

                            <tr>
                                <td><strong><?php echo $equipa->nome; ?></strong></td>
                                <?php if (!empty($juri)) {

                                    foreach ($juri as $juri_id) { ?>
                                        <td><?php
                                            $juri_score = calculate_score($juri_id, $moment_id,$moment_meta_id_arr,$moment_meta_id_per_arr, $equipa->id);
                                            if($juri_score!='' && $juri_score!=0.00){
                                                $all_juri_score[] = $juri_score;
                                            }

                                            echo $juri_score;

                                            ?></td>
                                    <?php }

                                }
                                ?>
                                <td><?php
                                    if (!empty($all_juri_score)) {
                                        // Calculate the sum of all elements
                                        $sum = array_sum($all_juri_score);

                                        // Calculate the average
                                        $count = count($all_juri_score);
                                        $average = ($count > 0) ? ($sum / $count) : 0;
                                        echo number_format($average, 2);
                                    }
                                    ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </section>
<!--             <script>
                jQuery(document).ready(function () {
                    jQuery('#request-list').DataTable({
                        order: [[0, 'desc']],
                        searching: false,
                        info: false,
                        lengthChange: false
                    });
                });
            </script> -->
			 <script>
    jQuery(document).ready(function () {
        dataTable = jQuery('#request-list').DataTable({
            order: [[0, 'desc']],
            searching: false,
            info: false,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv','excel', 'pdf', 'print'
            ],
        });
    });
  </script>


        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            $('#competicao_id').change(function () {
                var competicao_id = $(this).val();
                if (!competicao_id) {
                    alert("selecione a competicao_id")
                } else {
                    // Make an AJAX call to fetch momento table data
                    $.ajax({
                        url: ajaxurl, // WordPress AJAX endpoint
                        type: 'POST',
                        data: {
                            action: 'fetch_momento_compe_data', // Custom action name
                            competicao_id: competicao_id
                        },
                        success: function (response) {
                            // Display the fetched data in the specified container
                            $('#momento_data').html(response);
                        },
                        error: function (error) {
                            console.error('Error fetching momento data:', error);
                        }
                    });
                }


            });
        });
    </script>