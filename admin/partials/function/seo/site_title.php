<?php
//简单SEO - 站点标题
if (!class_exists('Npcink_Seo_Site_Title')) {
    class Npcink_Seo_Site_Title
    {
        private static $option;

        public static function run($option)
        {
            self::$option = $option;
            remove_action('wp_head', '_wp_render_title_tag', 1);//移除默认标题
            add_action('wp_head', array(__CLASS__, 'title'),0);
        }
        public static function title()
        {
            echo '<title>' . self::$option . '</title>';
            echo "\n";
        }
    }
}
