<?php
/**
 * 安全选项
 */
if (!class_exists('Magick_Mixtrue_Safe')) {
    class Magick_Mixtrue_Safe
    {

        public function __construct()
        {

        }

        //加载
        public static function run()
        {
            add_action('init', array(__CLASS__, 'load_run'));

        }

        //准备
        public static function load_run()
        {
            

           

        }

       

    }
}
