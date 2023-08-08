<?php
//优化 安全
if (!class_exists('MaMi_Optimize_Secure')) {
    class MaMi_Optimize_Secure
    {
        //加载
        public static function run($config)
        {
            //获取选项
            $option =  MaMi_Admin::get_config($config, 'secure');

            //统一登录错误信息
            $replace_login_error = MaMi_Admin::get_config($option, 'replace_login_error');
            if ($replace_login_error) {
                add_filter('login_errors', array(__CLASS__, 'remove_default_login_errors'));
            }
            //修改评论区样式中的管理员信息
            $modify_comment_user = MaMi_Admin::get_config($option, 'modify_comment_user');
            if ($modify_comment_user) {
                add_filter('comment_class', array(__CLASS__, 'true_completely_remove_css_class'));
            }

            //从RSS源和网站中删除WordPress版本
            $remove_RSS_version = MaMi_Admin::get_config($option, 'remove_RSS_version');
            if ($remove_RSS_version) {
                add_filter('the_generator', array(__CLASS__, 'remove_wp_version'));
            }
        }

        /**
         * 作用：覆盖默认登录错误提示信息
         * 来源：https://rudrastyh.com/wordpress/11-security-steps.html
         */
        public static function remove_default_login_errors()
        {
            return '<span class="dashicons dashicons-info-outline" style="
            color: #d63638;
            margin: 0 6px;
        "></span>用户名或密码不正确';
        }

        /**
         * 作用：修改评论区样式中的管理员信息
         * 来源：https://rudrastyh.com/wordpress/11-security-steps.html
         */
        public static function true_completely_remove_css_class($classes)
        {
            foreach ($classes as $key => $class) {
                if (strstr($class, "comment-author-")) {
                    unset($classes[$key]);
                }
            }
            return $classes;
        }

        /**
         * 作用：从RSS源和网站中删除WordPress版本
         * 来源：https://rudrastyh.com/wordpress/11-security-steps.html
         */
        public static function remove_wp_version()
        {
            return '';
        }
    } //end
}
