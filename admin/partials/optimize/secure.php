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

            
           

            //从RSS源和网站中删除WordPress版本
            $remove_RSS_version = MaMi_Admin::get_config($option, 'remove_RSS_version');
            if ($remove_RSS_version) {
                add_filter('the_generator', array(__CLASS__, 'remove_wp_version'));
            }
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
