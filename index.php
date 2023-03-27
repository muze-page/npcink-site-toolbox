<?php //沉默是金
use Carbon_Fields\Container;
use Carbon_Fields\Field;

require plugin_dir_path(__FILE__) . 'includes/carbon-fields/carbon-fields-plugin.php';

add_action('carbon_fields_register_fields', 'crb_attach_theme_options');
function crb_attach_theme_options()
{
    Container::make('theme_options', __('Theme Options'))
        ->add_fields(array(
            Field::make('text', 'crb_phone_numbers', 'Text Field'),
            Field::make('text', 'crb_phone_numberss', 'Text Field')
                ->set_visible_in_rest_api($visible = true),
        ));
}
//定义 REST API 端点（Endpoint）
// Register the REST API endpoints
add_action('rest_api_init', 'mytheme_register_rest_endpoints');

function mytheme_register_rest_endpoints()
{
    // Get theme options
    register_rest_route('mytheme/v1', 'theme-options', array(
        'methods' => 'GET',
        'callback' => 'mytheme_get_theme_options',
        //'permission_callback' => function () {
        //    return current_user_can('manage_options');
        //},
    ));

    // Update theme options
    register_rest_route('mytheme/v1', 'theme-options', array(
        'methods' => 'POST',
        'callback' => 'mytheme_update_theme_options',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));
}

//返回选项值
function mytheme_get_theme_options($request)
{
    $fields = [
        'crb_phone_numbers',
        'crb_phone_numberss',
    ];

    $options = [];
    foreach ($fields as $field) {
        $options[$field] = carbon_get_theme_option($field);
    }

    return $options;
    //return "666";
}

//更新选项
function mytheme_update_theme_options($request)
{
    if (isset($request['my_option'])) {
        $options = $request->get_params();

        $options = array_merge(carbon_get_theme_option(), $options);

        Carbon_Admin::update_site_options($options);
        return '主题选项更新成功！';
    } else {
        return '没有对主题选项进行更新。'+$request['my_option'];
    }
}

function mytheme_update_theme_optionsssss(WP_REST_Request $request)
{
    $params = $request->get_params();

    foreach ($params as $key => $value) {
        // Sanitize the option values as needed
        $sanitized_value = sanitize_text_field($value);

        // Update the option value in Carbon Fields
        Carbon_Admin::update_site_option($key, $sanitized_value);
    }

    return array(
        'success' => true,
        'message' => 'Options updated successfully',
    );
}
