<?php

/**
 * 未登录隐藏指定标签下的文章
 */

if (!class_exists('Npcink_Page_Hide_Tag')) {
    class Npcink_Page_Hide_Tag
    {
        private static $id_array; //配置
        public static function run($array)
        {
            self::$id_array = $array;
            add_action('pre_get_posts', array(__CLASS__, 'exclude_posts_by_tag')); //隐藏标签下的文章
        }

        //隐藏指定标签下的文章
        public static function exclude_posts_by_tag($query)
        {
            if (!is_admin() && !is_user_logged_in() && $query->is_main_query()) {
                $excluded_tag_ids = self::$id_array; // 要隐藏的标签ID数组

                // 检查是否在标签页
                if ($query->is_tag($excluded_tag_ids)) {
                    $query->set('tag__not_in', $excluded_tag_ids); // 排除特定标签
                }
            }
        }
    }
}
