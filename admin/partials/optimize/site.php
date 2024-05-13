<?php
//优化 站点
if (!class_exists('MaMi_Optimize_Site')) {
    class MaMi_Optimize_Site
    {
        //加载
        public static function run($config)
        {

            //获取选项
            $option =  MaMi_Admin::get_config($config, 'site');

            //禁止网站title中的 “-” 被转义
            $no_escape = MaMi_Admin::get_config($option, 'no_escape');
            if ($no_escape) {
                add_filter('run_wptexturize', '__return_false');
            };

           
        }



       
    }
}
