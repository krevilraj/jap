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
    .container__wrapper input[type="email"],
    .container__wrapper input[type="number"],.container__wrapper input[type="password"] {
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
<?php require_once(JAP_PATH . 'views/admin/juris/insert.php'); ?>
<div class="container__wrapper">
    <form action="" method="POST" id="jap__form">
        <input type="hidden" name="jap_juris_nonce"
               value="<?php echo wp_create_nonce('jap_juris_nonce'); ?>">
        <div class="container status__box">
            <div class="col-12">
                <input type="text" placeholder="Nome da juri" name="nome" required/>
                <input type="email" placeholder="Email" name="email" required/>
                <input type="password" placeholder="Password" name="password" required/>
                <label for="competicao_id">Competição associada</label>
                <select name="competicao_id" id="competicao_id">
                    <option></option>

                    <?php
                    foreach ($competicao_list as $index => $data): ?>
                        <option value="<?php echo $data['id']; ?>"><?php echo $data['nome']; ?></option>
                    <?php endforeach; ?>

                </select>

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
        $(document).on("change", ".select__all__checkbox input", function () {

            var equipaGroupoWrapper = $(this).closest(".equipa_groupo_wrapper");
            if (this.checked) {
                // Find the checkboxes within the .equipa_wrapper of the current .equipa_groupo_wrapper
                equipaGroupoWrapper.find(".equipa_wrapper input[type='checkbox']").prop('checked', this.checked);
                $(this).siblings('span').html('Unselect all');
            }else{
                equipaGroupoWrapper.find(".equipa_wrapper input[type='checkbox']").prop('checked', false);
                $(this).siblings('span').html('Select all');

            }
        });


    });


</script>

