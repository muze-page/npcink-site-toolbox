<?php

defined('ABSPATH') || exit;

/**
 * 未登录隐藏指定分类下的文章
 */

if (!class_exists('MaBox_Page_Hide_Category')) {
    class MaBox_Page_Hide_Category implements MaBox_Module_Interface
    {
        private static $id_array; //分类数组
        private static $tip_content; //提示信息
        public static function run($config = array())
        {
            self::$id_array = MaBox_Admin::get_config($config, 'category_id', array());
            self::$tip_content = MaBox_Admin::get_config($config, 'tip_content', '');
            add_action('the_content', array(__CLASS__, 'restrict_content_for_specific_categories'));
            add_action('wp_footer', array(__CLASS__, 'hide_download_for_restricted_categories'));
        }

        public static function restrict_content_for_specific_categories($content)
        {
            $restricted_category_ids = self::$id_array;

            if (in_category($restricted_category_ids)) {
                if (!MaBox_Helpers::is_logged_in()) {
                    $content = wp_kses_post(self::$tip_content);
                }
            }
            return $content;
        }

        public static function hide_download_for_restricted_categories()
        {
            if (MaBox_Helpers::is_logged_in()) {
                return;
            }

            if (in_category(self::$id_array)) {
                echo '<style>.b2-down-box, .down-box, .post-download, .download-box, .m-box.down { display: none !important; }</style>';
            }
        }
    }
}
