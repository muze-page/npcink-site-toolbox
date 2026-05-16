<?php
//外链跳转中间页
//拿到的链接：
$raw_url = isset($_GET['url']) ? wp_unslash($_GET['url']) : '';

// 安全验证：仅允许有效 URL
if (!empty($raw_url) && filter_var($raw_url, FILTER_VALIDATE_URL)) {
    $external_url = esc_url_raw($raw_url);
} else {
    $external_url = '';
}

//网站名：
$site_name = ' ' . get_bloginfo('name') . ' ';


//ico图标
$favicon_url = get_site_icon_url();

//准备路径
$url = plugin_dir_url(__FILE__)."css/" ;
