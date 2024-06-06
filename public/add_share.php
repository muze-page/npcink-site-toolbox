<?php
//添加分享按钮


if (!class_exists('Npcink_Public_Add_Share')) {
    class Npcink_Public_Add_Share
    {
        private static $config; //分类数组
        public static function run()
        {
            //self::$config = $option;
            //加载HTML
            add_action('wp_footer', array(__CLASS__, 'add_share_html'));

            //加载css和jS
            //使用动作钩子，加载这个函数到前台
            add_action('wp_enqueue_scripts', array(__CLASS__, 'magick_load_vue'));
        }
        public static function magick_load_vue()
        {

            wp_enqueue_style('唯一CSS名', plugin_dir_url(__FILE__) . 'share/share.css', array(), '1.0.0', 'all');
            //注册
            wp_register_script('唯一js名', plugin_dir_url(__FILE__) . 'share/share.js', array(), '1.0.0', true);
            //加载
            wp_enqueue_script('唯一js名');
        }




        //添加HTML
        public static function add_share_html()
        {

            echo '
            <div id="react_public"></div>
            ';
        }
    }
}
