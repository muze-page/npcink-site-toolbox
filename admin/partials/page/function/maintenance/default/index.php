<?php

defined('ABSPATH') || exit;

//默认带图
include plugin_dir_path((__FILE__)) . '../index.php'; // 获取数据

$mabox_logo = $mabox_file_url . 'default/tips.svg';
wp_die(
    '<div style="text-align:center">

    <img src="' . esc_url($mabox_logo) . '" alt="' . esc_attr(self::$blogname) . '" /><br /><br />' . wp_kses_post($mabox_countdown_content) . '</div>',
    esc_html($mabox_page_title),
    array('response' => '503')
);
