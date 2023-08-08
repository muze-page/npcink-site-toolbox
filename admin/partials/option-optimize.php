<?php

/**
 * 优化选项
 */
if (!class_exists('Magick_Mixtrue_Optimize')) {
    class Magick_Mixtrue_Optimize
    {

        //加载
        public static function run()
        {
            add_action('init', array(__CLASS__, 'load'));
        }
        //准备
        public static function load()
        {
            //获取设置选项值
            $config = MaMi_Admin::get_seting('optimize');

            /**
             * 优化 - 站点
             */
            require_once plugin_dir_path(__FILE__) . 'optimize/site.php';
            MaMi_Optimize_Site::run($config);

            /**
             * 优化 - 媒体
             */
            require_once plugin_dir_path(__FILE__) . 'optimize/medium.php';
            MaMi_Optimize_Medium::run($config);

            /**
             * 优化 - 评论
             */
            require_once plugin_dir_path(__FILE__) . 'optimize/comment.php';
            MaMi_Optimize_Comment::run($config);

            /**
             * 优化 - 安全
             */
            require_once plugin_dir_path(__FILE__) . 'optimize/secure.php';
            MaMi_Optimize_Secure::run($config);


            /**
             * 优化 - 其他
             */
            require_once plugin_dir_path(__FILE__) . 'optimize/other.php';
            MaMi_Optimize_Other::run($config);














            /**
             * 禁用
             */

            //禁用更新
            if (carbon_get_theme_option('cmma_opt_ban_update')) {
                self::run_ban_update();
            }



            //未登录模糊文章内图片
            if (carbon_get_theme_option('cmma_control_login_dim_content_img')) {
                //判断，没有登录
                if (!is_user_logged_in()) {
                    add_action('wp_footer', array(__CLASS__, 'n_yingcang_css'));
                }
            }
        }





        /**
         * 禁用
         */

        /**
         * 效果：禁用更新
         * 来源：https://www.npc.ink/15932.html
         */
        public static function run_ban_update()
        {
            remove_action('init', 'wp_schedule_update_checks'); // 关闭更新检查定时作业
            wp_clear_scheduled_hook('wp_version_check'); // 移除已有的版本检查定时作业
            wp_clear_scheduled_hook('wp_update_plugins'); // 移除已有的插件更新定时作业
            wp_clear_scheduled_hook('wp_update_themes'); // 移除已有的主题更新定时作业
            wp_clear_scheduled_hook('wp_maybe_auto_update'); // 移除已有的自动更新定时作业
            add_filter('automatic_updater_disabled', '__return_true'); // 彻底关闭自动更新
            remove_action('admin_init', '_maybe_update_core'); // 移除后台内核更新检查
            remove_action('load-plugins.php', 'wp_update_plugins'); // 移除后台插件更新检查
            remove_action('load-update.php', 'wp_update_plugins');
            remove_action('load-update-core.php', 'wp_update_plugins');
            remove_action('admin_init', '_maybe_update_plugins');
            remove_action('load-themes.php', 'wp_update_themes'); // 移除后台主题更新检查
            remove_action('load-update.php', 'wp_update_themes');
            remove_action('load-update-core.php', 'wp_update_themes');
            remove_action('admin_init', '_maybe_update_themes');
        }



        /**
         * 未登录模糊文章内图片
         */
        public static function n_yingcang_css()
        {
            echo '<style>

    /*仅模糊文章内图片*/
    .entry-content img {
    -webkit-filter: blur(10px)!important;
      -moz-filter: blur(10px)!important;
      -ms-filter: blur(10px)!important;
      filter: blur(6px)!important;}
      .entry-content img:before{
        content:"登录可见";
      }
      </style>';
        }
    }
}
