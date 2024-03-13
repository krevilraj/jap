<style>
    .d-none {
        display: none;
    }

    .container__wrapper .container {
        background-color: #fff;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-right: 20px;
        margin-bottom: 20px;
    }

    .container__wrapper input[type="text"],
    .container__wrapper input[type="email"],
    .container__wrapper input[type="number"], .container__wrapper input[type="password"] {
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

    .equipa_wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .momento_title {
        margin: 0;
        padding: 10px 20px;
        background: white;
        border: 1px solid #ccc;
        display: inline-block;
        border-radius: 20px;
        font-size: 13px;
        position: absolute;
        top: -19px;
        margin-left: 20px;
    }

    .equipa_wrapper {
        padding-block: 20px;
        padding-inline: 20px;
        border: 1px solid #ccc;

    }

    .equipa_groupo_wrapper {
        position: relative;
        margin-top: 40px;

    }

    .select__all__checkbox {
        position: absolute;
        right: 11px;
        top: 9px
    }

</style>
<h1>Juris Adicionar</h1>

<!-- insert logic here -->
<?php require_once(JAP_PATH . 'views/admin/juris/update.php'); ?>
<?php require_once(JAP_PATH . 'views/template/message_box.php'); ?>
<div class="container__wrapper">
    <form action="<?php echo home_url($_SERVER['REQUEST_URI']);
    ?>" method="POST" id="jap__form">
        <input type="hidden" name="jap_juris_nonce"
               value="<?php echo wp_create_nonce('jap_juris_nonce'); ?>">
        <input type="hidden" name="juri_id" value="<?php echo $juri->ID ?>">
        <div class="container status__box">
            <div class="col-12">
                <?php

                $competicao_id = get_user_meta($juri->ID, 'competicao_id', true); ?>
                <input type="text" placeholder="Nome da juri" name="nome" value="<?php echo $juri->user_login ?? '' ?>"
                       required/>
                <input type="email" placeholder="Email" name="email" value="<?php echo $juri->user_email ?>" required/>
                <input type="password" placeholder="Password" name="password"/>
                <label for="cars">Competição associada</label>
                <select name="competicao_id" id="competicao_id">
                    <option></option>

                    <?php
                    global $wpdb;
                    $table_name = TABLE_COMPETICAO;
                    $competicao_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY id DESC", ""), ARRAY_A);
                    foreach ($competicao_list as $index => $data): ?>
                        <option value="<?php echo $data['id']; ?>" <?php if ($data['id'] == $competicao_id) echo 'selected="selected"' ?> ><?php echo $data['nome']; ?></option>
                    <?php endforeach; ?>

                </select>

                <div id="momento_data">

                    <?php
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
                        foreach ($momento_data as $momento_index => $momento) { ?>
                            <div class="equipa_groupo_wrapper">
                                <div class="select__all__checkbox d-none">
                                    <input type="checkbox"
                                           id="checkbox__<?php echo sanitize_title($momento->title); ?>">
                                    <span>Select all</span>
                                </div>
                                <h3 class="momento_title"><?php echo $momento->title; ?></h3>
                                <input type="hidden" name="moment_id[<?php echo $momento_index; ?>][]"
                                       value="<?php echo $momento->id; ?>">
                                <div class="equipa_wrapper">
                                    <?php
                                    $equipa_list = get_equipas_all();
                                    foreach ($equipa_list as $equipa_item) {
                                        if (check_moment_group($equipa_item->id, $momento->id)) {
                                            ?>
                                            <div style="display: inline-block;margin-right:15px;">
                                                <h4><?php echo $equipa_item->nome; ?></h4>

                                                <?php
                                                $groupo_list = get_equipa_groupo($equipa_item->id);
                                                foreach ($groupo_list as $index => $groupo_item) {
                                                    if (check_group_moment($groupo_item->id, $momento->id)) {
                                                        ?>
                                                        <input type="hidden"
                                                               name="equipa_id[<?php echo $momento_index; ?>][]"
                                                               value="<?php echo $equipa_item->id; ?>">
                                                        <input type="checkbox"
                                                               class="groupo_equipa"
                                                               data-equipa_id="<?php echo $equipa_item->id; ?>"
                                                               data-groupo_id="<?php echo $groupo_item->id; ?>"
                                                               data-moment_id="<?php echo $momento->id; ?>"
                                                               data-juri_id="<?php echo $juri->ID; ?>"
                                                               id="<?php echo sanitize_title($groupo_item->nome) . $index . $momento_index ?>"
                                                               name="groupo[<?php echo $momento_index; ?>][]"
                                                            <?php echo get_groupo_equipa_checkbox($equipa_item->id, $groupo_item->id, $momento->id, $juri->ID); ?>
                                                               value="<?php echo $groupo_item->id; ?>">
                                                        <label for="<?php echo sanitize_title($groupo_item->nome) . $index . $momento_index ?>"><?php echo $groupo_item->nome; ?></label>
                                                        <br>
                                                    <?php }
                                                } ?>
                                            </div>

                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>


                        <?php }
                    } else {
                        echo '<p>No data found for the selected competicao_id</p>';
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


<script>
    jQuery(document).ready(function ($) {
        $('#competicao_id').change(function () {
            var competicao_id = $(this).val();

            // Make an AJAX call to fetch momento table data
            $.ajax({
                url: ajaxurl, // WordPress AJAX endpoint
                type: 'POST',
                data: {
                    action: 'fetch_juris_momento_data', // Custom action name
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
        });
        // Use event delegation for dynamically created elements
        $(document).on("change", "#competicao_id", function () {
            // Display a confirmation dialog
            var confirmChange = confirm('Are you sure you want to change competicao?');

            // If the user confirms, update the select value and submit the form
            if (confirmChange) {
                // Get the selected value from the #competicao_id select
                var newCompeticaoValue = $("#competicao_id").val();

                // Submit the form
                $(".submit__btn").click();
            } else {
                // If the user cancels, reset the select value (if needed)
                // $("#competicao_id").val(originalValue); // Uncomment and replace 'originalValue' with the original value if you want to reset the value
            }
        });
        $(document).on("change", ".select__all__checkbox input", function () {

            var equipaGroupoWrapper = $(this).closest(".equipa_groupo_wrapper");
            if (this.checked) {
                // Find the checkboxes within the .equipa_wrapper of the current .equipa_groupo_wrapper
                equipaGroupoWrapper.find(".equipa_wrapper input[type='checkbox']").prop('checked', this.checked);
                $(this).siblings('span').html('Unselect all');
            } else {
                equipaGroupoWrapper.find(".equipa_wrapper input[type='checkbox']").prop('checked', false);
                $(this).siblings('span').html('Select all');

            }
        });

        // Use event delegation for dynamically added checkboxes
        $(document).on("change", '.groupo_equipa', function () {
            // Your existing code for handling .groupo_equipa change event
            var equipaId = $(this).data('equipa_id');
            var groupoId = $(this).data('groupo_id');
            var momentoId = $(this).data('moment_id');
            var juriId = $(this).data('juri_id');
            var isChecked = $(this).prop('checked');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    equipa_id: equipaId,
                    groupo_id: groupoId,
                    momento_id: momentoId,
                    juri_id: juriId,
                    is_checked: isChecked,
                    action: 'fetch_juris_momento_equipa_change',
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });


    });


</script>

