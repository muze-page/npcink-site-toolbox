<?php

/**
 * 页面 权限
 */

if (!class_exists('Npcink_Page_Jurisdiction')) {
    class Npcink_Page_Jurisdiction
    {
        public static function run($option)
        {


            $category_id = MaBox_Admin::get_config($option, 'category_id');
            //分类数组是非空数字数组才开启
            // if (!empty($category_id)) {
            //添加分类数据接口
            require_once plugin_dir_path(__FILE__) . 'interface_category_data.php';
            Npcink_Interface_Category_Data::run();
            // }
            //隐藏指定分类
            // $remove_single_link = MaBox_Admin::get_config($option, 'remove_single_link');
            //  if ($remove_single_link === true) {
            //     require_once plugin_dir_path(__FILE__) . 'single_remove_link.php';
            //     Npcink_Single_Remove_Link::run();
            //  }

           
        }
       
    }
}
