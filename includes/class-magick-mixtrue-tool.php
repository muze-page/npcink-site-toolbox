<?php
/**
 * 一些公共函数
 */
if (!class_exists('Magick_Mixtrue_Tool')) {
    class Magick_Mixtrue_Tool
    {

        /**
         * 判断指定主题是否启用，若使用了该主题则返回true
         * 期待传入主题名  'Twenty Twenty'
         */
        public static function theme_active($theme_name)
        {
            $theme = wp_get_theme(); // 获取当前主题
            if ($theme_name == $theme->name || $theme_name == $theme->parent_theme) {
                //启用该主题
                return true;
            } else {
                //没有启用该主题
                return false;
            }
        }

        /**
         * 判断指定插件是否启用，若该插件启用则返回true
         * 期待传入插件目录，例如'advanced-custom-fields-pro/acf.php'
         */
        public static function plugin_active($plugin_position)
        {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
            if (is_plugin_active($plugin_position)) {
                //已启用
                return true;

            } else {
                //没有启用该插件
                return false;
            }
        }

        /**
         * 调试用，打印各种数据
         *
         */
        public static function p($data)
        {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }

        /**
         * 调试用，打印当前页面的$hook参数
         */
        public static function display_page_hook($hook)
        {
            echo '<h1 style="color: crimson;text-align: center;">' . esc_html($hook) . '</h1>';
        }
        /**
         * 调试
         * 查看页面参数
         */

        //add_action('admin_enqueue_scripts', array(&$this, 'display_page_hook'));

        /**
         * 创建一个方法，在后台顶部显示一个通知
         */
        public static function magick_admin_notice_acfs($content)
        {
            ?>
        <div class = 'notice notice-error '>
        <p><?php _e($content, 'sample-text-domain');
            ?></p>
        </div>
        <?php
}

        /**
         * 时间很重要
         */
        public static function get_time()
        {
            date_default_timezone_set("Asia/Shanghai");
            $a = strtotime(date("Y-m-d H:i:s")); //当前时间戳
            $todaytime = strtotime("today"); //今日起始时间戳

            return array(
                'a' => array(
                    date("Y-m-d H:i:s", $todaytime),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 1),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 2),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 3),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 4),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 5),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 6),
                ),
                'b' => array(
                    date("Y-m-d H:i:s", $todaytime - 8 * 60 * 60),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 1 - 8 * 60 * 60),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 2 - 8 * 60 * 60),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 3 - 8 * 60 * 60),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 4 - 8 * 60 * 60),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 5 - 8 * 60 * 60),
                    date("Y-m-d H:i:s", $todaytime - 24 * 60 * 60 * 6 - 8 * 60 * 60),
                ),
            );
        }

    } //end
}
//显示当前页hook
//add_action('admin_enqueue_scripts', array('Magick_Mixtrue_Tool', 'display_page_hook'));