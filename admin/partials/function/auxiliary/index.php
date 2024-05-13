<?php

/**
 * 辅助功能
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
        }
    }
}
