<?php

defined('ABSPATH') || exit;

//默认带图
include plugin_dir_path((__FILE__)) . '../index.php'; // 获取数据

$logo = $file_url . 'default/tips.svg';
wp_die(
    '<div style="text-align:center">

    <img src="' . esc_url($logo) . '" alt="' . esc_attr(self::$blogname) . '" /><br /><br />' . wp_kses_post($countdown_content) . '</div>',
    esc_html($page_title),
    array('response' => '503')
);
