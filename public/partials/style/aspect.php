<?php
//外观特效
if (!class_exists('MaMi_Style_Aspect')) {
    class MaMi_Style_Aspect
    {
        //选项值
        private static $option;
        //加载
        public static function run($config)
        {
            //获取选项
            $option =  MaMi_Admin::get_config($config, 'aspect');

            //传值
            self::$option = $option;

            /**
             * 网页整体变灰
             */
            $site_grey =  MaMi_Admin::get_config($option, 'site_grey');
            if ($site_grey) {
                add_action('wp_footer', array(__CLASS__, 'site_grey'));
            }
        }

        //网站变灰
        public static function site_grey()
        {

            echo '<style type="text/css">
            /*网站整体灰白 - Npcink*/
            html {
                -webkit-filter: grayscale(0.95); /* webkit */
                -moz-filter: grayscale(0.95); /*firefox*/
                -ms-filter: grayscale(0.95); /*ie9*/
                -o-filter: grayscale(0.95); /*opera*/
                filter: grayscale(0.95);
            }
            </style>';
        }
    }
}
