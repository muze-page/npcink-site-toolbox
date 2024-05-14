<?php

/**
 * 效果：百度统计
 * 来源：
 */
if (!class_exists('Npcink_Baidu_Tonji')) {
    class Npcink_Baidu_Tonji
    {

        private static $option;
        public static function run($config)
        {
            self::$option = $config;
            add_action('wp_footer', array(__CLASS__,'magick_display_platform_css'));
        }
        public static function magick_display_platform_css()
        {
            echo '<script>';
            echo self::$option;
            echo '</script>';
        }
    }
}
