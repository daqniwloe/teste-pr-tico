<?php
/**
 * Plugin Name:       AASP Custom Products Report
 * Description:       Exibe um relatório de produtos jurídicos com dados customizados.
 * Version:           1.0
 * Author:            Danilo
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'aasp_add_report_admin_page');
function aasp_add_report_admin_page() {
    add_menu_page(
        'Relatório de Produtos Jurídicos', // Título da Página
        'Produtos Jurídicos',             // Título do Menu
        'manage_options',                 // Permissão
        'aasp-juridical-report',          // Slug do menu
        'aasp_render_report_page',        // Função que renderiza a página
        'dashicons-analytics',            // Ícone
        25                                // Posição no menu
    );
}

function aasp_render_report_page() {
    global $wpdb;

    $items_per_page = 2;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $items_per_page;

    $products = $wpdb->get_results($wpdb->prepare(
        "SELECT
            p.ID,
            p.post_title,
            (SELECT COUNT(*)
             FROM {$wpdb->prefix}woocommerce_order_items oi
             LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
             WHERE oim.meta_key = '_product_id' AND oim.meta_value = p.ID
            ) as order_count
        FROM
            {$wpdb->prefix}posts p
        WHERE
            p.post_type = 'product'
            AND p.post_status = 'publish'
        GROUP BY
            p.ID
        ORDER BY
            p.post_title ASC
        LIMIT %d
        OFFSET %d",
        $items_per_page,
        $offset
    ));

    // Contagem total de produtos para a paginação
    $total_items = $wpdb->get_var("SELECT COUNT(ID) FROM {$wpdb->prefix}posts WHERE post_type = 'product' AND post_status = 'publish'");
    $total_pages = ceil($total_items / $items_per_page);

    ?>
    <div class="wrap">
        <h1>Relatório de Produtos Jurídicos</h1>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Descrição Técnica</th>
                    <th scope="col">Complexidade</th>
                    <th scope="col">Horas Estimadas</th>
                    <th scope="col">Nº de Pedidos</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td>
                                <strong>
                                    <a href="<?php echo get_edit_post_link($product->ID); ?>" target="_blank">
                                        <?php echo esc_html($product->post_title); ?>
                                    </a>
                                </strong>
                            </td>
                            <td>
                                <?php
                                // Verifica se a função do Carbon Fields existe antes de chamar
                                if (function_exists('carbon_get_post_meta')) {
                                    echo wpautop(carbon_get_post_meta($product->ID, 'aasp_technical_description'));
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (function_exists('carbon_get_post_meta')) {
                                    echo esc_html(ucfirst(carbon_get_post_meta($product->ID, 'aasp_complexity_level')));
                                }
                                ?>
                            </td>
                             <td>
                                <?php
                                if (function_exists('carbon_get_post_meta')) {
                                    echo esc_html(carbon_get_post_meta($product->ID, 'aasp_estimated_hours'));
                                }
                                ?>
                            </td>
                            <td><?php echo esc_html($product->order_count); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">Nenhum produto encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1) : ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <span class="pagination-links">
                        <?php
                        echo paginate_links([
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => __('&laquo;'),
                            'next_text' => __('&raquo;'),
                            'total' => $total_pages,
                            'current' => $current_page,
                        ]);
                        ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}