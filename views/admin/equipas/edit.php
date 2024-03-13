<style>
    .container__wrapper .container {
        background-color: #fff;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-right: 20px;
        margin-bottom: 20px;
    }

    .container__wrapper input[type="text"],
    .container__wrapper input[type="number"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;

    }

    .container__wrapper input[type="file"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #f2f2f2;
        cursor: pointer;
        background: white;
        border: unset;
    }

    .container__wrapper .momento__item_wrapper {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .container__wrapper .momento_area {
        flex-basis: calc(33.33% - 18px);
        margin-right: 20px;
        margin-bottom: 20px;
        background-color: #f2f2f2;
        padding: 20px;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .container__wrapper .momento_area:nth-child(3n) {
        margin-right: 12px;
    }

    .container__wrapper button {
        display: block;
        padding: 10px;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .container__wrapper button:hover {
        background-color: #444;
    }

    .momento__wrapper .close__btn {
        position: absolute;
        right: -7px;
        top: -7px;
    }

    .momento__wrapper, .momento_area {
        position: relative;
    }

    .momento_area .close__btn {
        position: absolute;
        right: -7px;
        top: -7px;
    }

    .momento__item_wrapper > .momento_area:first-of-type .close__btn {
        display: none;
    }

    .whole_total_wrapper > .momento__wrapper:first-of-type > .close__btn {
        display: none;
    }

    .close__btn {
        cursor: pointer;
    }

    .submit__btn {
        width: auto !important;
        padding-inline: 35px !important;
        background-color: #2271b1 !important;

    }

    .submit_wrapper {
        text-align: right;
        display: flex;
        flex-direction: row-reverse;
        margin-top: 20px;
        margin-right: 20px;
    }

    .add_outro_momento {
        margin-right: 20px;
    }

    .bullet__number {
        background: black;
        color: white;
        width: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        height: 25px;
        position: absolute;
        top: 6px;
        left: 6px;
    }

    .bc-red {
        border-color: red;
        border: 1px solid red;
    }

    .status__box {
        display: flex;
        justify-content: space-between;
    }

    .status__box .col-12 {
        width: 100%;
    }

    .status__box .col-7 input:last-child {
        margin-bottom: 0px;
    }

    .status__box .col-2 {
        width: 24%;
        padding-left: 47px;
        border: 1px solid #ccc;
    }

    .status__box select {
        width: 100%;
        max-width: 100%;
        padding: 8px;
    }
</style>
<h1>Atualização da equipe</h1>

<?php if (isset($equipa)): ?>
    <div class="container__wrapper">
        <?php require_once(JAP_PATH . 'views/admin/equipas/update.php'); ?>
        <?php require_once(JAP_PATH . 'views/template/message_box.php'); ?>
        <form action="" method="POST" id="jap__form">
            <input type="hidden" name="jap_equipas_nonce"
                   value="<?php echo wp_create_nonce('jap_equipas_nonce'); ?>">
            <input type="hidden" name="equipa_id"
                   value="<?php echo $equipa->id; ?>"">
            <div class="container status__box">
                <div class="col-12">

                    <label for="equipa_nome">Nome da equipa</label><br>

                    <select name="equipa_id" id="equipa_nome" required>
                        <option value="<?php echo $equipa->id; ?>"><?php echo $equipa->nome; ?></option>


                    </select><br><br>

                    <label for="competicao_id">Competição associada</label><br>
                    <select name="competicao_id" id="competicao_id" required>
                        <option></option>

                        <?php
                        $competicao_list = get_competicao_list();
                        if (isset($competicao_list)) {
                            foreach ($competicao_list as $index => $data) { ?>
                                <option value="<?php echo $data['id']; ?>" <?php if($equipa->competicao_id == $data['id']) echo 'selected="selected"'?> ><?php echo $data['nome']; ?></option>
                                <?php
                            }
                        } ?>

                    </select><br><br>

                    <div id="momento_data">
                        <?php
                        global $wpdb;
                        // Get the competicao_id from the AJAX request
                        $competicao_id = $equipa->competicao_id;
                        $equipa_id = $equipa->id;


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
                                    <?php $groupo_list = get_equipa_select_groupo($equipa_id);
                                    foreach ($groupo_list as $groupo) { ?>
                                        <option value="<?php echo $groupo->id; ?>" <?php check_equipa_groupo($groupo->id,$momento->id,$equipa_id,$equipa->user_id);?>><?php echo $groupo->nome; ?></option>
                                    <?php } ?>


                                </select><br><br>

                            <?php }
                        }
                        ?>

                    </div>

                </div>


            </div>


            <div class="submit_wrapper">
                <button type="submit" name="submit" class="submit__btn">Submit</button>
            </div>
        </form>
    </div>
<?php endif; ?>
<script>
    jQuery(document).ready(function ($) {
        $('#competicao_id').change(function () {
            var competicao_id = $(this).val();
            var equipa_id = $("#equipa_nome").val();
            if (!equipa_id) {
                alert("selecione a equipe")
            } else {
                // Make an AJAX call to fetch momento table data
                $.ajax({
                    url: ajaxurl, // WordPress AJAX endpoint
                    type: 'POST',
                    data: {
                        action: 'fetch_momento_data', // Custom action name
                        competicao_id: competicao_id,
                        equipa_id: equipa_id
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

