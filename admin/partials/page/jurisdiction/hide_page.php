<?php

defined('ABSPATH') || exit;

/**
 * 未登录隐藏指定页面
 */

if (!class_exists('MaBox_Page_Hide_Page')) {
    class MaBox_Page_Hide_Page implements MaBox_Module_Interface
    {
        private static $id_array; //分类数组
        private static $tip_content; //提示信息
        public static function run($config = array())
        {
            self::$id_array = MaBox_Admin::get_config($config, 'page_id', array());
            self::$tip_content = MaBox_Admin::get_config($config, 'tip_content', '');
            add_action('the_content', array(__CLASS__, 'restrict_content_for_specific_categories'));
        }

        public static function restrict_content_for_specific_categories($content)
        {
            // 定义受限的分类ID数组
            $page_ids = array_map('absint', (array) self::$id_array);

            //当前是页面类型，且当前页面ID在指定数组中
            if (is_page() && in_array(absint(get_the_ID()), $page_ids, true)) {
                // 如果用户未登录，则将文章内容替换为登录提示
                if (!MaBox_Helpers::is_logged_in()) {
                    $content = wp_kses_post(self::$tip_content);
                }
            }
            return $content;
        }
    }
}
