<style>
    .col-left, .col-right {
        background-color: #fff;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-right: 20px;
        margin-bottom: 20px;
    }

    .col-left {
        width: 35%;
    }

    .col-right {
        width: 60%;
    }

    #equipas-container {
        display: flex;
    }

    .button_wrapper {
        text-align: right;
    }

    .groupos_wrapper .button {
        border-radius: 50%;
    }

    .groupos_wrapper, .groupo_input, .mt-15 {
        margin-top: 15px !important;
    }

    .groupo_input {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .groupo_input input, .form-input {
        width: 100%;
        margin-right: 10px;
    }

    #new-category-form label {
        font-weight: 500;
    }

    #new-category-form .groupo_input:first-child button {
        visibility: hidden;
    }

    #new-category-form input[type="text"],
    #new-category-form input[type="number"] {
        display: block;
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;

    }

    .remove-group {
        height: 26px;
        width: 34px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
        margin-top: 8px !important;
    }

    .remove-group i {
        font-size: 11px;
    }
</style>
<?php
wp_enqueue_script('jap_datatables_js');
wp_enqueue_style('jap_datatables_css');
?>
<h1>Equipes e seus grupos</h1>
<div id="equipas-container">

    <div class="col-left">
        <?php require_once(JAP_PATH . 'views/admin/equipas/insert.php');?>

        <form id="new-category-form" method="POST">
            <!-- Input for category name -->
            <label for="name">Equipes Nome:</label>
            <input type="text" id="name" class="form-input" name="name" required>
            <div class="groupos_wrapper">
                <div class="groupo_input">
                    <input name="groupo[]" type="text" placeholder="Nome do grupo" required>
                    <button type="button" class="button button-danger remove-group"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <button type="button" class="button button-primary mt-15" id="add_group"><i class="fas fa-plus"></i>
                Adicionar grupo
            </button>

            <!-- Button to add new category -->
            <div class="button_wrapper">
                <button type="submit" class="button button-primary">Submit</button>
            </div>

        </form>
    </div>
    <div class="col-right">
        <table id="example" class="display" style="width:100%">
            <thead>
            <tr style="text-align: left">
                <th>Equipes Nome</th>
                <th>Groupo</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Equipas 1</td>
                <td>Group1 , Group2</td>

            </tr>
            <tr>
                <td>Equipas 2</td>
                <td>Group1 , Group2</td>

            </tr>
            <tr>
                <td>Equipas 3</td>
                <td>Group1 , Group2</td>

            </tr>
            <tr>
                <td>Equipas 4</td>
                <td>Group1 , Group2</td>

            </tr>

            </tbody>
        </table>
    </div>


</div>

<script>
    jQuery(document).ready(function ($) {
        // Add group
        $('#add_group').on('click', function () {
            var newGroupInput = $('.groupo_input:first').clone();
            newGroupInput.find('input').val(''); // Clear input value
            $('.groupos_wrapper').append(newGroupInput);
        });

        // Remove group
        $('.groupos_wrapper').on('click', '.remove-group', function () {
            $(this).closest('.groupo_input').remove();
        });
        new DataTable('#example', {
            lengthChange: false,
            info: false
        });

    });
</script>