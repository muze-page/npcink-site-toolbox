<?php
//优化 站点
if (!class_exists('MaMi_Optimize_Site')) {
    class MaMi_Optimize_Site
    {
        //加载
        public static function run($config)
        {

            //获取选项
            $option =  MaMi_Admin::get_config($config, 'site');

            //禁止网站title中的 “-” 被转义
            $no_escape = MaMi_Admin::get_config($option, 'no_escape');
            if ($no_escape === true) {
                add_filter('run_wptexturize', '__return_false');
            };

            //禁用自动更新
            $renew = MaMi_Admin::get_config($option, 'renew');
            if ($renew === true) {
                require_once plugin_dir_path(__FILE__) . 'ban_update.php';
                Npcink_Ban_Update::run();
            }


            //从RSS源和网站中删除WordPress版本
            $remove_RSS_version = MaMi_Admin::get_config($option, 'remove_RSS_version');
            if ($remove_RSS_version === true) {
                require_once plugin_dir_path(__FILE__) . 'remove_wp_version.php';
                Npcink_Remove_WP_Version::run();
            }
        }
    }
}
