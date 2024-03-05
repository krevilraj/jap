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

    /*.submit__btn {*/
    /*    width: auto !important;*/
    /*    padding-inline: 35px !important;*/
    /*    background-color: #2271b1 !important;*/

    /*}*/

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

    .status__box .col-7 {
        width: 70%;
    }

    .status__box .col-7 input:last-child {
        margin-bottom: 0px;
    }

    .status__box .col-2 {
        width: 24%;
        padding-left: 47px;
        border: 1px solid #ccc;
    }

    .left-side {
        width: 50%;
    }

    .status__box .col-2 {
        display: flex;
    }

    .status__box h4 {
        margin-bottom: 10px;
    }
</style>
<h1>Competição Adicionar</h1>

<?php
$competicao = $data['competicao'];
?>


<!-- insert logic here -->
<?php require_once(JAP_PATH . 'views/admin/competicao/update.php'); ?>
<div class="container__wrapper">
    <form action="" method="POST" id="jap__form">
        <input type="hidden" name="jap_competicao_nonce"
               value="<?php echo wp_create_nonce('jap_competicao_nonce'); ?>">
        <input type="hidden" name="competicao_id" value="<?php echo $competicao->id; ?>">
        <div class="container status__box">
            <div class="col-7">
                <input type="text" placeholder="NOME DA COMPETIÇÃO" name="nome"
                       value="<?php echo $competicao->nome ?? ''; ?>"
                       required/>
                <input type="file" id="image-upload"/>

            </div>
            <div class="col-2">
                <div class="left-side">
                    <h4>Author</h4>
                    <p><?php
                        $current_user = wp_get_current_user();
                        echo my_get_users_name($competicao->user_id);
                        ?>
                    </p>
                </div>
                <div class="right-side">
                    <h4>Status</h4>
                    <select name="status" id="">
                        <option value="Published" <?php echo $competicao->status == 'Published' ? 'selected="selected"' : ''; ?>>
                            Published
                        </option>
                        <option value="Draft" <?php echo $competicao->status == 'Draft' ? 'selected="selected"' : ''; ?>>
                            Draft
                        </option>
                    </select>
                </div>

            </div>

        </div>
        <div class="whole_total_wrapper">
            <?php
            $momento_list = $data['momentos_list'];
            if (isset($momento_list) && !empty($momento_list)) {
                foreach ($momento_list as $index => $moment_item) {

                    ?>
                    <div class="container momento__wrapper">
                        <div class="close__btn" data-moments_id="<?php echo $moment_item->id ?? ''; ?>"><img src="<?php echo JAP_URL; ?>assets/image/cancel.png" alt=""></div>
                        <div class="bullet__number"><?php echo $index + 1; ?></div>
                        <div class="momento__item_wrapper">
                            <input type="text" name="<?php echo "momento_name[$index][]"; ?>"
                                   value="<?php echo $moment_item->title ?? ''; ?>" placeholder="NOME DO MOMENTO"
                                   required/>
                            <input type="hidden" name="<?php echo "momento_id[$index][]"; ?>"
                                   value="<?php echo $moment_item->id ?? ''; ?>"
                                   required/>
                            <?php
                            $observation_list = get_observation($moment_item->id);
                            foreach ($observation_list as $observation_item) { ?>


                                <div class="momento_area">
                                    <div class="close__btn" data-observacao_id="<?php echo $observation_item->id ?? ''; ?>"><img src="<?php echo JAP_URL; ?>assets/image/cancel.png"
                                                                 alt="">
                                    </div>
                                    <input type="hidden" name="<?php echo "observacao_id[$index][]"; ?>"
                                           value="<?php echo $observation_item->id ?? ''; ?>">
                                    <input type="text" name="<?php echo "observacao[$index][]"; ?>"
                                           value="<?php echo $observation_item->title ?? ''; ?>"
                                           placeholder="OBSERVAÇÃO" required/> <br>
                                    <input type="number" name="<?php echo "peso_da_nota[$index][]"; ?>"
                                           value="<?php echo $observation_item->peso_da_nota ?? ''; ?>"
                                           placeholder="PESO DA NOTA"
                                           required/><br>
                                    <input type="text" name="<?php echo "o_que_avaliamos[$index][]"; ?>"
                                           value="<?php echo $observation_item->o_que_avaliamos ?? ''; ?>"
                                           placeholder="O QUE AVALIAMOS"
                                           required/><br>
                                </div>
                            <?php } ?>

                        </div>
                        <button type="button" class="add_momento">+ ADICIONAR MAIS CAMPOS DE AVALIAÇÃO</button>
                    </div>
                    <?php
                }
            }
            ?>
        </div>


        <button type="button" class="add_outro_momento">+ ADICIONAR OUTRO MOMENTO</button>
        <div class="submit_wrapper">
            <button type="submit" name="submit" class="submit__btn button button-primary">Submit</button>
        </div>
    </form>
</div>

<script>
    jQuery(document).ready(function ($) {
        // Initialize the index
        var index = 0;
        var formSubmitted = false;
        // Add more fields for a particular moment
        $(document).on("click", ".add_momento", function () {
            var lastMomentoItemWrapper = $(this).siblings(".momento__item_wrapper");
            var clonedMomentoArea = lastMomentoItemWrapper.find(".momento_area:last-child").clone();
            clonedMomentoArea.find(".close__btn").removeAttr("data-observacao_id");
            // Remove hidden input fields from the cloned element
            clonedMomentoArea.find("input[type='hidden']").remove();

            // Reset values of cloned input fields
            clonedMomentoArea.find("input[type='text'], input[type='number']").val('');

            lastMomentoItemWrapper.append(clonedMomentoArea);
        });

        // Add another moment
        $(document).on("click", ".add_outro_momento", function () {
            var containerWrapper = $(this).siblings(".whole_total_wrapper");
            var clonedMomentoWrapper = containerWrapper.find(".momento__wrapper").last().clone();

            // Remove hidden input fields from the cloned element
            clonedMomentoWrapper.find("input[type='hidden']").remove();

            // Remove data attribute for observation id
            clonedMomentoWrapper.find(".close__btn").removeAttr("data-moments_id");
            clonedMomentoWrapper.find(".close__btn").removeAttr("data-observacao_id");

            // Parse bullet__num to int, increment, and update HTML
            var bullet__num = parseInt(containerWrapper.find(".bullet__number:last").html());
            clonedMomentoWrapper.find(".bullet__number").html(bullet__num + 1);
            index = bullet__num;
            clonedMomentoWrapper.find("input[name^='momento_name']").attr('name', 'momento_name[' + index + '][]').val('');
            clonedMomentoWrapper.find("input[name^='observacao']").attr('name', 'observacao[' + index + '][]').val('');
            clonedMomentoWrapper.find("input[name^='peso_da_nota']").attr('name', 'peso_da_nota[' + index + '][]').val('');
            clonedMomentoWrapper.find("input[name^='o_que_avaliamos']").attr('name', 'o_que_avaliamos[' + index + '][]').val('');

            containerWrapper.append(clonedMomentoWrapper);
        });

        // Remove momento__wrapper on close button click
        $(document).on("click", ".momento__wrapper > .close__btn", function () {
            var moment_id = $(this).data("moments_id");
            if(moment_id){
                $.ajax({
                    url: ajaxurl, // WordPress AJAX endpoint
                    type: "POST",
                    data: {
                        action: "delete_momento_row", // Custom action name
                        moment_id: moment_id,
                    },
                    success: function (response) {
                        // Handle the success response (if needed)
                        console.log("Row deleted successfully");
                    },
                    error: function (error) {
                        // Handle the error response (if needed)
                        console.error("Error deleting row:", error);
                    },
                });
            }

            $(this).closest(".momento__wrapper").remove();
        });

        $(document).on("click", ".momento_area > .close__btn", function () {
            var observation_id = $(this).data("observacao_id");

            if (observation_id) {
                // Make an AJAX call to delete the row
                $.ajax({
                    url: ajaxurl, // WordPress AJAX endpoint
                    type: "POST",
                    data: {
                        action: "delete_momento_meta_row", // Custom action name
                        observation_id: observation_id,
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

            $(this).closest(".momento_area").remove();
        });

        // Form submission validation
        $("#jap__form").submit(function (event) {
            var isValidationError = false;
            $(".momento__wrapper").removeClass('bc-red');
            // Iterate over each .momento__wrapper
            $(".momento__wrapper").each(function () {
                var totalPesoDaNota = 0;
                var moment_number = $(this).find('.bullet__number').html();
                // Iterate over .momento_area within each .momento__wrapper
                $(this).find(".momento_area").each(function () {
                    var pesoDaNota = parseFloat($(this).find("input[name^='peso_da_nota']").val()) || 0;
                    totalPesoDaNota += pesoDaNota;
                });

                // Check if the total sum is not equal to 100%
                if (totalPesoDaNota !== 100) {
                    alert("The sum of 'PESO DA NOTA' in " + moment_number + " moment should be 100%.");
                    $(this).addClass('bc-red');
                    isValidationError = true;
                    return false; // Exit the loop
                }
            });

            if (isValidationError) {
                event.preventDefault(); // Prevent form submission

            }
        });
    });
</script>

