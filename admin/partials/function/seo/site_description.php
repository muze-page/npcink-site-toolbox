<?php
//简单SEO - 站点描述
if (!class_exists('Npcink_Seo_Site_Description')) {
    class Npcink_Seo_Site_Description
    {
        private static $option;

        public static function run($option)
        {
            self::$option = $option;
            add_action('wp_head', array(__CLASS__, 'description'),0);
        }
        public static function description()
        {
            echo '<meta name="description" content="' . self::$option . '" />';
            echo "\n";
        }
    }
}
