
<?php
    if (isset($juris_list) && count($juris_list) > 0): ?>
    <?php
    wp_enqueue_script('jap_datatables_js');
    wp_enqueue_style('jap_datatables_css');
    ?>
    <section class="jap_admin_list">
        <div>
            <h2> <h1>lista de Juris</h1>
            </h2>
        </div>
        <div class="table__wrapper">
            <table border="1" cellpadding="10" id="request-list" class="request-list">
                <thead>
                <tr>
                    <th><?php esc_html_e('IDº', 'jap'); ?></th>
                    <th><?php esc_html_e('Nome de Juri', 'jap'); ?></th>
                    <th><?php esc_html_e('Email', 'jap'); ?></th>
                    <th><?php esc_html_e('Competição associada', 'jap'); ?></th>
                    <?php if (is_admin()): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;
                foreach ($juris_list as $index => $data): ?>
                    <tr>
                        <td><?php echo $data['ID']; ?></td>
                        <td><?php echo my_get_users_name($data['ID']); ?></td>
                        <td><?php echo $data['user_email']; ?></td>
                        <td><?php echo get_juri_competicao($data['ID']); ?></td>


                        <?php if (is_admin()): ?>
                            <td>
                                <a   class="jap__action__admin  jap__edit__admin"  href="admin.php?page=edit-juris&juri_id=<?php echo $data['ID'];?>">
                                    <button>
                                        <i class="far fa-edit"></i>
                                    </button>
                                </a>
                                <a class="jap__action__admin  jap__delete__admin"  onclick="return confirm( 'Tem certeza de que deseja excluir esta competição?' )" href="admin.php?page=delete-juris&id=<?php echo $data['ID'];?>">
                                    <button>
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </a>


                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>
    </section>
    <script>
        jQuery(document).ready(function () {
            jQuery('#request-list').DataTable({
                order: [[0, 'desc']],
            });
        });
    </script>

<?php
else:
    get_no_result();
endif;
?>

