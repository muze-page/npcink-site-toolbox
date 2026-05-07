<?php

/**
 * 登录页 安全
 */
if (!class_exists('Npcink_Login_Security')) {
    class Npcink_Login_Security
    {
        public static function run($security)
        {
            $option = $security;

            //登录页验证码
            $login_code = MaBox_Admin::get_config($option, 'login_code');
            if ($login_code !== 'false') {
                //登录添加验证码
                require_once plugin_dir_path(__FILE__) . 'login_verify.php';
                Npcink_Login_Verify::run($login_code);
            }
        }
    }
}
