<?php

/**
 * 页面 权限
 */

if (!class_exists('Npcink_Page_Jurisdiction')) {
    class Npcink_Page_Jurisdiction
    {
        public static function run($option)
        {

            //分类数组
            $category_id = MaBox_Admin::get_config($option, 'category_id');

            //标签数组
            $tag_id = MaBox_Admin::get_config($option, 'tag_id');

            //合并数组
            $mergedArray = array_merge($category_id,  $tag_id);

            //分类数组或标签数组是非空数组才开启接口
            if (!empty($mergedArray)) {
                //添加分类数据接口
                require_once plugin_dir_path(__FILE__) . 'interface_category_data.php';
                Npcink_Interface_Category_Data::run();

                //添加隐藏文章提示
                require_once plugin_dir_path(__FILE__) . 'hide_prompt.php';
                Npcink_Page_Hide_Prompt::run($mergedArray);
            }

            //隐藏指定分类
            if (!empty($category_id)) {
                require_once plugin_dir_path(__FILE__) . 'hide_category.php';
                Npcink_Page_Hide_Category::run($category_id);
            }

            //隐藏指定标签
            if (!empty($category_id)) {
                require_once plugin_dir_path(__FILE__) . 'hide_tag.php';
                Npcink_Page_Hide_Tag::run($tag_id);
            }
        }
    }
}
