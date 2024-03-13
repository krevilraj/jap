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
<h1>Equipas Adicionar</h1>


<div class="container__wrapper">
    <?php require_once(JAP_PATH . 'views/admin/equipas/insert_equipas.php'); ?>
    <?php require_once(JAP_PATH . 'views/template/message_box.php'); ?>
    <form action="" method="POST" id="jap__form">
        <input type="hidden" name="jap_equipas_nonce"
               value="<?php echo wp_create_nonce('jap_equipas_nonce'); ?>">
        <div class="container status__box">
            <div class="col-12">

                <label for="equipa_nome">Nome da equipa</label><br>

                <select name="equipa_id" id="equipa_nome" required>
                    <option >Nome da equipa</option>
                    <?php
                    if (isset($data['equipa_nome_list'])) {
                        foreach ($data['equipa_nome_list'] as $index => $data) { ?>
                            <option value="<?php echo $data['id']; ?>"><?php echo $data['nome']; ?></option>
                            <?php
                        }
                    } ?>

                </select><br><br>

                <label for="competicao_id">Competição associada</label><br>
                <select name="competicao_id" id="competicao_id" required>
                    <option></option>

                    <?php
                    if (isset($competicao_list)) {
                        foreach ($competicao_list as $index => $data) { ?>
                            <option value="<?php echo $data['id']; ?>"><?php echo $data['nome']; ?></option>
                            <?php
                        }
                    } ?>

                </select><br><br>

                <div id="momento_data">

                </div>

            </div>


        </div>


        <div class="submit_wrapper">
            <button type="submit" name="submit" class="submit__btn">Submit</button>
        </div>
    </form>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('#competicao_id').change(function () {
            var competicao_id = $(this).val();
            var equipa_id = $("#equipa_nome").val();
            if(!equipa_id){
                alert("selecione a equipe")
            }else{
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

