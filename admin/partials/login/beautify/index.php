<?php

/**
 * 登录页 美化
 */
if (!class_exists('Npcink_Login_Beautify')) {
    class Npcink_Login_Beautify
    {
        public static function run($beautify)
        {
            //登录页LOGO改为首页链接
            $modify_login_link = MaMi_Admin::get_config($beautify, 'modify_login_link');
            if ($modify_login_link === true) {
                require_once plugin_dir_path(__FILE__) . '/change_login_logo_link.php'; //加载文件
                Npcink_Login_Change_Logo_Link::run(); //执行
            }

             //移除登录页语言选择器
             $remove_langue = MaMi_Admin::get_config($beautify, 'remove_langue');
             if ($remove_langue=== true) {
                require_once plugin_dir_path(__FILE__) . '/remove_login_lang_select.php'; //加载文件
                Npcink_Login_Remove_Lang_Select::run(); //执行
             }
         

            //自定义登录页
            $custom_login_page = MaMi_Admin::get_config($beautify, 'custom_login_page');
            if ($custom_login_page === true) {
                //自定义登录页
                require_once plugin_dir_path(__FILE__) . '/custom_login_page.php';
                Npcink_Login_Custom_Page::run($beautify);
            }
        }
    }
}
