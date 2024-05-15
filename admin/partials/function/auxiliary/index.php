<?php

/**
 * 功能 - 辅助功能
 */
if (!class_exists('MaBox_Function_Auxiliary')) {
    class MaBox_Function_Auxiliary
    {
        public static function run($option)
        {
            //加载文章统计
            $single_count = MaBox_Admin::get_config($option, 'single_count');
            if ($single_count === true) {
                //文章统计页面
                require_once plugin_dir_path(__FILE__) . '/census-single.php';
                MaBox_Census_Single::run();
            }

            //屏蔽恶意关键词搜索
            $no_malice_key = MaBox_Admin::get_config($option, 'no_malice_key'); //状态
            $keyword_arr = MaBox_Admin::get_config($option, 'malice_keu_content'); //关键词数组
            if ($no_malice_key === true) {
                //屏蔽恶意关键词搜索
                require_once plugin_dir_path(__FILE__) . 'ban_malice_search.php';
                Npcink_Ban_Malice_Search::run($keyword_arr);
            }

            //百度统计
            $baidu_tonji = MaBox_Admin::get_config($option, 'baidu_tonji'); //关键词数组
            if ($baidu_tonji !== '' && $baidu_tonji !== false) {
                //屏蔽恶意关键词搜索
                require_once plugin_dir_path(__FILE__) . 'baidu_tonji.php';
                Npcink_Baidu_Tonji::run($baidu_tonji);
            }

            //谷歌统计
            $google_tonji = MaBox_Admin::get_config($option, 'google_tonji'); //关键词数组
            if ($google_tonji !== '' && $google_tonji !== false) {
                //屏蔽恶意关键词搜索
                require_once plugin_dir_path(__FILE__) . 'google_tonji.php';
                Npcink_Google_Tonji::run($google_tonji);
            }

            //必应统计
            $biying_tonji = MaBox_Admin::get_config($option, 'biying_tonji'); //关键词数组
            if ($biying_tonji !== '' && $biying_tonji !== false) {
                //屏蔽恶意关键词搜索
                require_once plugin_dir_path(__FILE__) . 'biying_tonji.php';
                Npcink_Biying_Tonji::run($biying_tonji);
            }
        }
    }
}
