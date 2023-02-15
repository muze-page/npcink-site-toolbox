<?php
//文章统计菜单

//如何在当前页面加载js
if (!class_exists('Magick_Mixtrue_Census_Single')) {
    class Magick_Mixtrue_Census_Single
    {

        public function __construct()
        {

        }
        /**
         * 做一个函数，加载js
         */
        public function load_echarts()
        {
            //$loader = new Magick_Mixtrue_Loader;
            //$magick = new Magick_Mixtrue;
            //$plugin_admin = new Magick_Mixtrue_Admin($magick->get_plugin_name(), $magick->get_version());
            //$loader->add_action('admin_enqueue_scripts', $plugin_admin, 'census_scripts');

            echo "简单有趣的文本";
        }
    }
}
