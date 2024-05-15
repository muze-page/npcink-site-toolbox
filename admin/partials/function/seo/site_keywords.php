<?php
//简单SEO - 站点关键词
if (!class_exists('Npcink_Seo_Site_Keywords')) {
    class Npcink_Seo_Site_Keywords
    {
        private static $option;

        public static function run($option)
        {
            self::$option = $option;
            add_action('wp_head', array(__CLASS__, 'keywords'),0);
        }
        public static function keywords()
        {
            echo '<meta name="keywords" content="' . self::$option . '" />';
            echo "\n";
        }
    }
}
