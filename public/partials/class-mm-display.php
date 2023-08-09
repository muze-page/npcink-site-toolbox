<?php

/**
 * 为插件提供面向公众的视图
 *
 */

if (!class_exists('Magick_Mixtrue_Display')) {
    class Magick_Mixtrue_Display
    {

        public static function run()
        {
            add_action('wp', array(__CLASS__, 'load'));
        }

        public static function load()
        {
            //评论区添加表情
            //self::run_owo();
            ////烟花粒子特效
            //self::run_particle();


        }
    }
}

//这个文件应该主要由HTML和一点点PHP组成
