<?php


require_once WP_CONTENT_DIR . '/vendor/autoload.php';


\Carbon_Fields\Carbon_Fields::boot();

use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action('carbon_fields_register_fields', 'aasp_register_custom_product_fields');
function aasp_register_custom_product_fields() {
    Container::make('post_meta', __('Informações Jurídicas'))
        ->where('post_type', '=', 'product')
        ->add_fields([
            Field::make('rich_text', 'aasp_technical_description', 'Descrição Técnica')
                ->set_help_text('Descreva os detalhes técnicos do curso/conteúdo.'),

            Field::make('select', 'aasp_complexity_level', 'Nível de Complexidade')
                ->set_options([
                    'baixa' => 'Baixa',
                    'media' => 'Média',
                    'alta' => 'Alta',
                ])
                ->set_help_text('Selecione o nível de complexidade do conteúdo.'),

            Field::make('text', 'aasp_estimated_hours', 'Quantidade Estimada de Horas')
                ->set_attribute('type', 'number')
                ->set_help_text('Insira o número de horas estimadas para estudo.'),
        ]);
}


add_filter('woocommerce_product_tabs', 'aasp_add_juridical_info_tab');
function aasp_add_juridical_info_tab($tabs) {
    $tabs['juridical_info_tab'] = [
        'title'    => __('Informações Jurídicas', 'woocommerce'),
        'priority' => 50,
        'callback' => 'aasp_juridical_info_tab_content',
    ];
    return $tabs;
}


function aasp_juridical_info_tab_content() {
    global $product;

    echo '<h2>' . __('Detalhes do Conteúdo', 'woocommerce') . '</h2>';

    $technical_description = carbon_get_post_meta($product->get_id(), 'aasp_technical_description');
    $complexity_level = carbon_get_post_meta($product->get_id(), 'aasp_complexity_level');
    $estimated_hours = carbon_get_post_meta($product->get_id(), 'aasp_estimated_hours');

    if ($technical_description) {
        echo '<h4>Descrição Técnica</h4>';
        echo wpautop($technical_description); 
    }

    if ($complexity_level) {
        echo '<h4>Nível de Complexidade</h4>';
       
        echo '<p>' . ucfirst($complexity_level) . '</p>';
    }

    if ($estimated_hours) {
        echo '<h4>Horas Estimadas para Estudo</h4>';
        echo '<p>' . esc_html($estimated_hours) . ' horas</p>';
    }
}


add_action('woocommerce_single_product_summary', 'aasp_add_validation_button', 60);
function aasp_add_validation_button() {
    echo '<div id="aasp-validation-wrapper" style="margin-top: 20px;">';
    echo '<button type="button" id="aasp-validate-btn" class="button">Validar conteúdo jurídico com sistema externo</button>';
    echo '<div id="aasp-validation-spinner" style="display:none; margin-top:10px;">Validando... <span class="spinner is-active" style="float:none; vertical-align:middle;"></span></div>';
    echo '<div id="aasp-validation-message" style="margin-top:10px;"></div>';
    echo '</div>';
}

add_action('wp_enqueue_scripts', 'aasp_enqueue_validation_script');
function aasp_enqueue_validation_script() {

    if (is_product()) {

        $product_id = get_the_ID();

        
        wp_register_script(
            'aasp-validator',
            get_stylesheet_directory_uri() . '/js/validator.js',
            [],
            '1.0.1', 
            true 
        );

        // Passa dados do PHP para o JS de forma segura
        $script_data = [
            'productId'      => $product_id,
            'complexity'     => carbon_get_post_meta($product_id, 'aasp_complexity_level'),
            'estimatedHours' => carbon_get_post_meta($product_id, 'aasp_estimated_hours'),
            'apiUrl'         => 'https://mock-api.aasp.com.br/workflow/validate-product',
        ];
        wp_localize_script('aasp-validator', 'aaspProductData', $script_data);

       
        wp_enqueue_script('aasp-validator');
    }
}
