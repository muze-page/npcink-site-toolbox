<?php //沉默是金
function my_custom_function()
{
    $data1 = $_POST['data1'];
    $data2 = $_POST['data2'];

    // 处理请求，并生成响应数据
    $response = array(
        'status' => 'success',
        'message' => '处理下：Received data1=' . $data1 . ' and  data2=' . $data2,
    );

    // 返回响应数据
    wp_send_json($response);
}

// 注册动作钩子
add_action('wp_ajax_my_custom_function', 'my_custom_function');
add_action('wp_ajax_nopriv_my_custom_function', 'my_custom_function');

// 创建图片展示次数表
function create_image_view_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'image_views';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
      id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      image_id BIGINT(20) UNSIGNED NOT NULL,
      views INT(11) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
  ) $charset_collate;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
add_action('init', 'create_image_view_table');

// 处理图片展示次数ajax请求
function record_image_view()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'image_views';

    //获取图片ID
    $image_id = $_POST['image_id'];
    //获取图片时间
    $time = $_POST['time'];
    echo "<script>console.log('我打印了')</script>" . $image_id . $time;
    $views = 1;
    $views_query = $wpdb->prepare("SELECT views FROM $table_name WHERE image_id = %d", $image_id);
    $current_views = $wpdb->get_var($views_query);
    if ($current_views) {
        $views = $current_views + 1;
        $wpdb->update(
            $table_name,
            array('views' => $views),
            array('image_id' => $image_id)
        );
    } else {
        $wpdb->insert(
            $table_name,
            array(
                'image_id' => $image_id,
                'views' => $views,
            )
        );
    }
    exit;
}
add_action("wp_ajax_record_image_view", "record_image_view");
add_action("wp_ajax_nopriv_record_image_view", "record_image_view");

// 在 WordPress 后台管理界面中添加一个菜单链接
add_action('admin_menu', 'my_admin_menu');
function my_admin_menu()
{
    add_menu_page('Image Views', '广告统计', 'manage_options', 'image-views', 'show_image_views');
}

// 显示图片展示次数的函数
function show_image_views()
{
    global $wpdb;

    // 从数据库中读取所有图片的展示次数
    $table_name = $wpdb->prefix . 'image_views';
    $rows = $wpdb->get_results("SELECT * FROM $table_name");

    // 将数据以表格的形式展示出来
    echo '<h1>广告统计</h1>';
    echo '<table class="widefat">';
    echo '<thead><tr><th>图片ID</th><th>展示次数</th></tr></thead>';
    echo '<tbody>';
    foreach ($rows as $row) {
        echo sprintf('<tr><td>%s</td><td>%d</td></tr>', $row->image_id, $row->views);
    }
    echo '</tbody>';
    echo '</table>';
}
