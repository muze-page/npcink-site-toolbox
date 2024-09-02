<?php

/**
 * 未登录隐藏指定分类下的文章
 */

if (!class_exists('Npcink_Page_Hide_Category')) {
    class Npcink_Page_Hide_Category
    {
        private static $id_array; //分类数组
        private static $tip_content; //提示信息
        public static function run($array, $id_tip_content)
        {
            self::$id_array = $array;
            self::$tip_content = $id_tip_content;
            add_action('the_content', array(__CLASS__, 'restrict_content_for_specific_categories'));
        }

        public static function restrict_content_for_specific_categories($content)
        {
            // 定义受限的分类ID数组
            $restricted_category_ids = self::$id_array; // 将这里替换为你想要限制的分类ID数组

            // 检查文章是否属于受限的分类
            if (in_category($restricted_category_ids)) {
                if (!is_user_logged_in()) {
                    // 如果用户未登录，则将文章内容替换为登录提示
                    // 转义

                    $content = self::$tip_content;
                    add_action('wp_footer', array(__CLASS__, 'covert_content')); //使用jS隐藏文章内容
                }
            }
            return $content;
        }
        //覆盖文章内容
        public static function covert_content()
        {
            // 将 PHP 变量转义为 JavaScript 友好的格式
            // $tip_content = esc_js(self::$tip_content);
            // 只保留 HTML 标记
            $tip_content = wp_kses_post(self::$tip_content);
?>
            <script>
                // 获取 .entry-content 元素，文章内容
                const entryContent = document.querySelector(".entry-content");
                // 设置新的内容
                if (entryContent) {
                    entryContent.innerHTML = '<?php echo $tip_content ?>';
                }
            </script>
<?php
        }
    }
}
