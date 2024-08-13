<?php
//优化 媒体
if (!class_exists('MaBox_Optimize_Medium')) {
    class MaBox_Optimize_Medium
    {
        //加载
        public static function run($config)
        {
            //获取选项
            $option =  MaBox_Admin::get_config($config, 'medium');

            //自动给图片添加Alt标签
            $img_add_tag = MaBox_Admin::get_config($option, 'img_add_tag');
            if ($img_add_tag === true) {
                require_once plugin_dir_path(__FILE__) . 'image_add_tag.php';
                Npcink_Image_Add_Tag::run();
            }

            // 禁用自动生成的图片尺寸
            $no_auto_size = MaBox_Admin::get_config($option, 'no_auto_size');
            if ($no_auto_size === true) {
                require_once plugin_dir_path(__FILE__) . 'ban_auto_size.php';
                Npcink_Medium_Ban_Auto_Size::run();
            }

            //添加媒体库 SVG 图标支持
            $medium_add_svg = MaBox_Admin::get_config($option, 'medium_add_svg');
            if ($medium_add_svg === true) {
                require_once plugin_dir_path(__FILE__) . 'svg_support.php';
                Npcink_Medium_Svg_Support::run();
            }

            //媒体文件重命名
            $upload_auto_name = MaBox_Admin::get_config($option, 'upload_auto_name');
            if ($upload_auto_name !== 'false') {
                require_once plugin_dir_path(__FILE__) . 'image_rename.php';
                Npcink_Medium_Image_Rename::run($upload_auto_name);
            }
        }
    } //end
}
