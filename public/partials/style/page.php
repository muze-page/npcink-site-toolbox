<?php
//风格 特效
if (!class_exists('MaMi_Style_Page')) {
    class MaMi_Style_Page
    {
        //选项值
        private static $option;
        //加载
        public static function run($config)
        {
            //获取选项
            $option =  MaMi_Admin::get_config($config, 'page');

            //传值
            self::$option = $option;


           

            //圆角彩色背景标签云
            $color_tag = MaMi_Admin::get_config($option, 'color_tag');
            if ($color_tag) {
                add_filter('wp_tag_cloud', array(__CLASS__, 'colorCloud'), 1);
            }




          


            //评论区添加表情
            $comment_emote = MaMi_Admin::get_config($option, 'comment_emote');
            if ($comment_emote) {
                add_action('wp', array(__CLASS__, 'run_owo'));
            }
        }

        


        /**
         * 添加彩色标签云
         */
        public static function colorCloud($text)
        {
            $text = preg_replace_callback('|<a (.+?)>|i', array(__CLASS__, 'colorCloudCallback'), $text);
            return $text;
        }
        public static function colorCloudCallback($matches)
        {
            $text = $matches[1];
            $colors = array('F99', 'C9C', 'F96', '6CC', '6C9', '37A7FF', 'B0D686', 'E6CC6E');
            $color = $colors[dechex(rand(0, 7))];
            $pattern = '/style=(\'|\")(.*)(\'|\")/i';
            $text = preg_replace($pattern, "style=\"display: inline-block; *display: inline; *zoom: 1; color: #fff; padding: 1px 5px; margin: 0 5px 5px 0; background-color: #{$color}; border-radius: 3px; -webkit-transition: background-color .4s linear; -moz-transition: background-color .4s linear; transition: background-color .4s linear;\"", $text);
            $pattern = '/style=(\'|\")(.*)(\'|\")/i';
            return "<a $text>";
        }



       




        /**
         * 效果：评论区加载表情包
         * 来源：https://github.com/DIYgod/OwO
         */
        public static function run_owo()
        {
            /**
             * TODO:判断当前页面是否加载评论区
             */
            //获取当前页面的帖子对象
            $current_post = get_post();
            if ($current_post && $current_post->comment_status === 'open') {
                //加载js和css资源
                add_action('wp_enqueue_scripts', array(__CLASS__, 'load_owo_resouce'));
                //加载配置js
                add_action('wp_footer', array(__CLASS__, 'load_owo_comment_js'));
                //加载表情包位置
                add_filter('comment_form_defaults', array(__CLASS__, 'load_owo_content'));
            }
        }

        /**
         * 加载表情用资源
         */
        public static function load_owo_resouce()
        {
            wp_enqueue_script(
                MAGICK_MIXTURE_NAME . '_OwO-js',
                plugin_dir_url(dirname(__DIR__)) . 'js/OwO.min.js',
                array(),
                MAGICK_MIXTURE_VERSION,
                false
            );

            wp_enqueue_style(
                MAGICK_MIXTURE_NAME . '_OwO-css',
                plugin_dir_url(dirname(__DIR__)) . 'css/OwO.min.css',
                array(),
                MAGICK_MIXTURE_VERSION,
                'all'
            );
        }

        /**
         * 加载表情用JS
         */
        public static function load_owo_comment_js()
        {
            //输入框定位
            $target_id = 'comment';

            //拿到表情包用js地址
            $json_src = plugin_dir_url(dirname(__DIR__)) . 'json/OwO.json';
?>
            <script>
                let $src = '<?php echo $json_src ?>';
                let $target = '<?php echo $target_id ?>'
                var OwO_demo = new OwO({
                    logo: 'OωO表情',
                    container: document.getElementsByClassName('OwO')[0],
                    target: document.getElementById($target),
                    api: $src,
                    position: 'down',
                    width: '100%',
                    maxHeight: '250px'
                });
            </script>
<?php
        }

        /**
         * 加载表情用文件内容
         */
        public static function load_owo_content($default)
        {
            //$commenter = wp_get_current_commenter();
            $default['comment_field'] .= '<div class="OwO"></div>
        <style>
        .OwO {
            padding: 0 0 20px 0;
        }
        .OwO .OwO-body {
            position: initial!important;
        }
        </style>
        ';

            return $default;
        }
    }
}
