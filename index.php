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
    $table_name = $wpdb->prefix . 'npc_ad_count';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
      id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      identify BIGINT(20) UNSIGNED NOT NULL,
      click_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
  ) $charset_collate;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
//add_action('init', 'create_image_view_table');

// 处理图片展示次数ajax请求
function record_image_view()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'npc_ad_count';

    //获取图片ID
    $image_id = $_POST['image_id'];
    // 从 POST 请求中获取点击时间
    $time = $_POST['time'];
    echo "<script>console.log('我打印了')</script>" . $image_id . $time;
    // 插入记录到数据库中
    $wpdb->insert(
        $table_name,
        array(
            'identify' => $image_id,
            'click_time' => $time,
        )
    );

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

// 检查是否已经提交了表单
    $date_filter = isset($_POST['date_filter']) ? $_POST['date_filter'] : 'all';

// 定义可能的日期过滤器
    $date_filters = [
        'today' => '今天',
        'yesterday' => '昨天',
        'last_week' => '过去一周',
        'this_month' => '本月',
        'last_month' => '上月',
        'all' => '总计',
    ];

// 生成日期过滤器的选项
    $filter_options = '';
    foreach ($date_filters as $key => $label) {
        $selected = ($key === $date_filter) ? 'selected' : '';
        $filter_options .= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $label);
    }

// 检查是否已经提交了日期选择器表单
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// 修改 SQL 查询以依据日期过滤器对数据进行筛选
    $table_name = $wpdb->prefix . 'npc_ad_count';
    if ($start_date && $end_date) {
        $where_clause = sprintf("WHERE DATE(click_time) BETWEEN '%s' AND '%s'", $start_date, $end_date);
    } else {
        switch ($date_filter) {
            case 'today':
                $where_clause = "WHERE DATE(click_time) = CURDATE()";
                break;
            case 'yesterday':
                $where_clause = "WHERE DATE(click_time) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
                break;
            case 'last_week':
                $where_clause = "WHERE click_time >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
                break;
            case 'this_month':
                $where_clause = "WHERE YEAR(click_time) = YEAR(CURDATE()) AND MONTH(click_time) = MONTH(CURDATE())";
                break;
            case 'last_month':
                $where_clause = "WHERE PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM CURDATE()), EXTRACT(YEAR_MONTH FROM click_time)) = 1";
                break;
            default:
                $where_clause = "";
                break;
        }
    }

// 执行 SQL 查询
    $rows = $wpdb->get_results("SELECT identify, DATE(click_time) as date, COUNT(*) as count FROM $table_name $where_clause GROUP BY identify, DATE(click_time)");

// 将数据以表格的形式展示出来
    echo '<h1>广告统计</h1>';
    echo '<form method="post">';
    echo '<select name="date_filter">';
    echo $filter_options;
    echo '</select>';

// 在日期选择器中显示用户选择的日期
    echo '<label for="start_date">开始日期：</label>';
    echo sprintf('<input type="date" name="start_date" id="start_date" value="%s">', $start_date);
    echo '<label for="end_date">结束日期：</label>';
    echo sprintf('<input type="date" name="end_date" id="end_date" value="%s">', $end_date);

    echo '<input type="submit" value="筛选">';
    echo '</form>';
    echo '<table class="widefat">';
    echo '<thead><tr><th>ID</th><th>展示日期</th><th>展示次数</th></tr></thead>';
    echo '<tbody>';
    foreach ($rows as $row) {
        echo sprintf('<tr><td>%d</td><td>%s</td><td>%d</td></tr>', $row->identify, $row->date, $row->count);
    }
    echo '</tbody>';
    echo '</table>';}
