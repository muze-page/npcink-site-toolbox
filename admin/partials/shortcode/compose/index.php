<?php

/**
 * 功能：短代码 板式
 */
if (!class_exists('MaBox_ShortCode_Compose')) {
    class MaBox_ShortCode_Compose  extends MaBox_ShortCode
    {
        public static function runs($option)
        {
            //文章列表
            $single_list = MaBox_Admin::get_config($option, 'single_list');
            if ($single_list === true) {
                require_once plugin_dir_path(__FILE__) . 'single_list/index.php';
                MaBox_ShortCode_Single_List::run();
                //下拉中添加短代码
                //这里需要进行转义，不然会丢失部分短代码内容
                self::$option_list .= '
                <option value="[past_posts_display ids=&quot;1,2,3&quot; limit=&quot;10&quot;]">文章列表</option>
              ';
            }
        }
    } //end
}
