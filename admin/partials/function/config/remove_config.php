<?php

/**
 * 效果：移除插件设置选项内容
 */
if (!class_exists('MaBox_Config_Remove_Config')) {
    class MaBox_Config_Remove_Config
    {
        //MAGICK_MIXTURE_OPTION
        public static function run()
        {
            add_action('wp_head', array(__CLASS__, 'add_hello_header'));
        }
        public static  function add_hello_header()
        {
            echo '<div style="background-color: yellow; text-align: center;">你好</div>';
        }

        public static function remove_config()
        {
            //删除选项

            $deleted = delete_option('MAGICK_MIXTURE_OPTION');

            if ($deleted) {
                // 成功删除选项的逻辑
                echo '选项 MAGICK_MIXTURE_OPTION 已成功删除。';
            } else {
                // 未能删除选项的逻辑
                echo '无法删除选项 MAGICK_MIXTURE_OPTION。';
            }
        }
    }
}
