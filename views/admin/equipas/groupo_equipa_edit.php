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
    .groupo_input .index{
        visibility: hidden;
        position: absolute;
    }
</style>
<?php
wp_enqueue_script('jap_datatables_js');
wp_enqueue_style('jap_datatables_css');
?>
<h1>Equipes e seus grupos</h1>
<div id="equipas-container">

    <div class="col-left">
        <?php require_once(JAP_PATH . 'views/admin/equipas/update_groupo_equipa.php'); ?>
        <?php require_once(JAP_PATH . 'views/template/message_box.php'); ?>
        <form id="new-category-form" method="POST">
            <input type="hidden" name="jap_groupo_nonce"
                   value="<?php echo wp_create_nonce('jap_groupo_nonce'); ?>">
            <!-- Input for category name -->
            <label for="name">Equipes Nome:</label>
            <input type="hidden" name="id" value="<?php echo $equipa->id ?? ''; ?>">
            <input type="text" id="name" class="form-input" name="nome" value="<?php echo $equipa->nome ?? ''; ?>"
                   required>
            <div class="groupos_wrapper">
                <?php
                if (isset($data['groupo'])) {
                    $groupo = $data['groupo'];
                    foreach ($groupo as $index => $group) {
                        ?>
                        <div class="groupo_input">
                            <div class="index"><?php echo $index;?></div>
                            <input type="hidden" name="groupo_id[<?php echo $index;?>][]" value="<?php echo $group->id ?? ''; ?>">
                            <input name="groupo[<?php echo $index;?>][]" type="text" placeholder="Nome do grupo"
                                   value="<?php echo $group->nome ?? ''; ?>" required>
                            <button type="button" data-groupo_id="<?php echo $group->id ?? ''; ?>" class="button button-danger remove-group"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    <?php }
                } ?>
            </div>
            <button type="button" class="button button-primary mt-15" id="add_group"><i class="fas fa-plus"></i>
                Adicionar grupo
            </button>

            <!-- Button to add new category -->
            <div class="button_wrapper">
                <button type="submit" name="submit" class="button button-primary">Submit</button>
            </div>

        </form>
    </div>


</div>

<script>
    jQuery(document).ready(function ($) {
        // Add group
        $('#add_group').on('click', function () {
            var index;
            var newGroupInput = $('.groupo_input:last-child').clone();
            newGroupInput.find('input').val(''); // Clear input value
            index = parseInt(newGroupInput.find('.index').html());
            index++;
            newGroupInput.find('.index').html(index);
            $('.groupos_wrapper').append(newGroupInput);
            newGroupInput.find("input[type='hidden']").remove();
            newGroupInput.find("input[name^='groupo']").attr('name', 'groupo[' + index + '][]').val('');

        });

        // Remove group
        $('.groupos_wrapper').on('click', '.remove-group', function () {
            var groupo_id = $(this).data("groupo_id");

            if (groupo_id) {
                // Make an AJAX call to delete the row
                $.ajax({
                    url: ajaxurl, // WordPress AJAX endpoint
                    type: "POST",
                    data: {
                        action: "delete_groupo_row", // Custom action name
                        groupo_id: groupo_id,
                    },
                    success: function (response) {
                        // Handle the success response (if needed)
                        console.log("Row deleted successfully");
                        formSubmitted = true;
                    },
                    error: function (error) {
                        // Handle the error response (if needed)
                        console.error("Error deleting row:", error);
                    },
                });
            }
            $(this).closest('.groupo_input').remove();
        });
        new DataTable('#example', {
            lengthChange: false,
            info: false
        });

    });
</script>