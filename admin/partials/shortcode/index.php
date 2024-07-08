<?php

/**
 * 功能
 */
if (!class_exists('MaBox_ShortCode')) {
    class MaBox_ShortCode
    {
        //短代码下拉选项内容
        public static $option_list = "";

        public static function run()
        {

            //获取设置选项值
            $config = MaBox_Admin::get_seting('shortcode');

            /**
             * 短代码 - 板式
             */
            require_once plugin_dir_path(__FILE__) . '/compose/index.php';
            $compose =  MaBox_Admin::get_config($config, 'compose');
            MaBox_ShortCode_Compose::runs($compose);

            //经典编辑器添加下拉按钮
            if (!empty(self::$option_list)) {
                add_action('admin_init', array(__CLASS__, 'custom_function_for_media_buttons'));
            }
        }

        //经典编辑器，添加下拉按钮
        public static function custom_function_for_media_buttons()
        {
            if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
                add_action('media_buttons', array(__CLASS__, 'wzt_select'), 11);
                add_action('admin_head', array(__CLASS__, 'wzt_button'));
            }
        }
        // 后台编辑器添加下拉式按钮
        public static function wzt_select()
        {
            // 初始化选项列表，假设 self::$option_list 是一个静态变量并已经初始化
            $options = self::$option_list;
            // 开始构建下拉框的 HTML
            $html = '
  <select id="short_code_select">
      <option value="">选择短代码</option>
      ' . $options . '
  </select>';

            // 输出 HTML
            echo $html;
        }



        public static function wzt_button()
        {
?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $("#short_code_select").change(function() {
                        send_to_editor($("#short_code_select :selected").val());
                        return false;
                    });
                });
            </script>
<?php
        }
    } //end
}
