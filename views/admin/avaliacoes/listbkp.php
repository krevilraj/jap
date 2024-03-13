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
            <div class="equipa_wrapper">
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
                    <div class="equipa_item">
                        <h2><?php echo $equipa->nome; ?></h2>
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
                                        <th style="text-align: left">Avaliação</th>
                                        <th>Nota</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php global $wpdb;
                                    $table_equipas = TABLE_EQUIPAS;
                                    $table_juris = TABLE_JURIS_EQUIPAS_RANK;
                                    $table_momento_meta = TABLE_MOMENTO_META;
                                    $table_momento = TABLE_MOMENTO;
                                    $query = $wpdb->prepare("
    SELECT * 
    FROM $table_juris e
    JOIN $table_momento_meta r ON e.momento_meta_id = r.id
    WHERE e.momento_id = %d AND e.equipa_id = %d
", $moment_item->id, $equipa->id);

                                    $results = $wpdb->get_results($query);
                                    $total = 0; ?>
                                    <?php foreach ($results as $result_data) {
                                        $percentValue = $result_data->peso_da_nota;
                                        $total +=  $percentValue/100 * $result_data->equipa_rank;
//                                        $total += $result_data->equipa_rank ?? 0; ?>
                                        <tr>
                                            <td><?php echo $result_data->title; ?>
                                                | <?php echo $result_data->peso_da_nota; ?></td>
                                            <td style="text-align: center"><?php echo $result_data->equipa_rank; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th style="text-align: left">Total</th>
                                        <th style="text-align: center"><?php echo $total; ?></th>
                                    </tr>
                                    </tbody>
                                </table>

                            <?php $grand_total_equipa+=$total;

                            }
                            if($momento_count!=0){
                                $grand_total_equipa = $grand_total_equipa/$momento_count;
                                echo '<h4 style="display: flex;font-weight: bold;padding-left: 3px;
    justify-content: space-between;
    padding-right: 21px;"><span>Total</span>'.$grand_total_equipa.'</h4>';
                            }

                        } else {
                            echo '<p>Nenhum momento encontrado</p>';
                        } ?> </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>