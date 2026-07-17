<?php

defined('ABSPATH') || exit;

//暂停维护页

//网站名：
$mabox_site_name = get_bloginfo('name');

//准备资源链接
$mabox_file_url = plugin_dir_url(__FILE__);

//传来的值

//获取设置选项值
$mabox_config = MaBox_Admin::get_seting('page');
$mabox_function = MaBox_Admin::get_config($mabox_config, 'function');

//时间
$mabox_countdown_data = MaBox_Admin::get_config($mabox_function, 'countdown', array());

//组合成结束时间
$mabox_countdown_end = is_array($mabox_countdown_data)
    && isset($mabox_countdown_data[1])
    && is_string($mabox_countdown_data[1])
        ? trim($mabox_countdown_data[1])
        : '';
$mabox_countdown = '' !== $mabox_countdown_end ? $mabox_countdown_end . ':00' : '';

//标题
$mabox_countdown_title = MaBox_Admin::get_config($mabox_function, 'countdown_title');

//标题默认值
// $mabox_countdown_title = isset($mabox_countdown_title) && !empty($mabox_countdown_title) ? $mabox_countdown_title : "升级维护中";
if (isset($mabox_countdown_title) && empty($mabox_countdown_title)) {
    $mabox_countdown_title = '升级维护中';
}

//网页标题
$mabox_page_title = $mabox_countdown_title . ' - ' . $mabox_site_name;

//内容
$mabox_countdown_content_data = MaBox_Admin::get_config($mabox_function, 'countdown_content');

//转义
$mabox_countdown_content = html_entity_decode($mabox_countdown_content_data);

//内容默认值
if (empty($mabox_countdown_content)) {
    $mabox_countdown_content = '
    <h5> 抱歉，我们的网站正在维护中...</h5> 
    <p> 
    请倒计时结束后再回来，我们准备了全新的内容哦！
    </p>
    ';
}
