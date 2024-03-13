<style>
    .equipa_wrapper {
        display: flex;
        flex-wrap: wrap;
		    margin-top: 20px;
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
        background-color: #ccc;
    }

    .equipa_item h2:after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 2px;
        height: 1px;
        width: 95%;
        max-width: 255px;
        background-color: #ccc;
    }

    .equipa_item h2 {
        position: relative;
        padding-bottom: 15px;
    }

    .equipa_item h3 {
        color: #286075;
    }

    .equipa_title {
	margin-bottom: 0;
    border-bottom: 2px solid #286075;
    padding-bottom: 20px;
    color: #286075;
    margin-top: 40px;
    font-size: 20px;

    }
</style>
<section class="jap_admin_list">
    <div>
        <h1>Lista de Avaliação</h1>
    </div>
    <div class="table__wrapper">
        <div class="jap_filter">
            <form action="/wp-admin/admin.php?page=avaliacoes">
                <label for="competicao_id">Competição</label>
                <select name="competicao_id" id="competicao_id">
                    <?php
                    $competicao_id = '';
                    $competicao_list = get_competicao_list();
                    if (!isset($_GET['competicao_id'])) {
                        if (!empty($competicao_list)) {
                            pre($competicao_list);
                            $competicao_id = $competicao_list[0]['id'];
                            echo $competicao_id;
                        }

                    } else {
                        $competicao_id = $_GET['competicao_id'];

                    }

                    if (isset($competicao_list)) {
                        foreach ($competicao_list as $index => $data) { ?>
                            <option value="<?php echo $data['id']; ?>" <?php if ($competicao_id == $data['id']) echo 'selected="selected"' ?> ><?php echo $data['nome']; ?></option>
                            <?php
                        }
                    } ?>
                    <input type="hidden" name="page" value="avaliacoes">
                </select>
                <button type="submit" class="submit__btn button button-primary">Submit</button>
            </form>
            <div class="result__wrapper">
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

                foreach ($results as $equipa) { ?>
                    <h2 class="equipa_title"><?php echo $equipa->nome; ?></h2>
                    <div class="equipa_wrapper">
                        <?php
                        $table_equipas_rank = TABLE_JURIS_EQUIPAS_RANK;
                        $table_juris = TABLE_USERS;
                        $sql = "
                                SELECT DISTINCT e.user_id, user_login
                                FROM $table_equipas_rank e
                                JOIN $table_juris r ON e.user_id = r.id
                                WHERE e.equipa_id = $equipa->id
                            ";
                        $query = $wpdb->prepare($sql);
                        $results = $wpdb->get_results($query);
                        foreach ($results as $user_info) {
                            $user_id = $user_info->user_id;
                            ?>
                            <div class="equipa_item">
                                <h2><?php echo $user_info->user_login; ?></h2>
                                <?php $momento = get_momento($competicao_id);
                                $momento_count = count($momento);
                                $grand_total_equipa = 0;
                                if (!empty($momento)) {
                                    foreach ($momento as $moment_item) {
                                        ?>
                                        <h3><?php echo $moment_item->title; ?></h3>

                                        <table>
                                            <thead>
                                            <tr>
                                                <th style="text-align: left;">Avaliação</th>
                                                <th style="text-align: right;">Nota</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php global $wpdb;
                                            $table_equipas = TABLE_EQUIPAS;
                                            $table_juris = TABLE_JURIS_EQUIPAS_RANK;
                                            $table_momento_meta = TABLE_MOMENTO_META;
                                            $table_momento = TABLE_MOMENTO;

                                            $sql = "
                                                            SELECT * 
                                                            FROM $table_juris e
                                                            JOIN $table_momento_meta r ON e.momento_meta_id = r.id
                                                            WHERE e.momento_id = $moment_item->id AND e.equipa_id = $equipa->id AND e.user_id = $user_id
                                                        ";
                                            $query = $wpdb->prepare($sql);

                                            $results = $wpdb->get_results($query);
                                            $total = 0; ?>
                                            <?php foreach ($results as $result_data) {
                                                $percentValue = $result_data->peso_da_nota;
                                                $total += $percentValue / 100 * $result_data->equipa_rank; ?>
                                                <tr>
                                                    <td><?php echo $result_data->title; ?>
                                                        | <?php echo $result_data->peso_da_nota; ?></td>
                                                    <td style="text-align: right"><?php echo $result_data->equipa_rank; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <th style="text-align: left">Total</th>
                                                <th style="text-align: right"><?php
                                                    if(!empty($results)){
//                                                         $total = $total/count($results);
                                                        $formatted_total = number_format($total, 2);
                                                        echo $formatted_total;
                                                    }
                                                    ?></th>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                        $grand_total_equipa += $total;
                                    }
                                    if ($momento_count != 0) {
										
                                        $grand_total_equipa = $grand_total_equipa / $momento_count;
                                        $formatted_grand_total = number_format($grand_total_equipa, 2);
                                        echo '<h4 style="display: flex;font-weight: bold;padding-left: 3px;
    justify-content: space-between;
   "><span>Total</span>' . $formatted_grand_total . '</h4>';
                                    }
                                }

                                ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>