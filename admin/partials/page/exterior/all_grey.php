<?php

/**
 * 效果：全站变灰
 * 来源：
 */
if (!class_exists('Npcink_Page_All_Grey')) {
    class Npcink_Page_All_Grey
    {
        public static function run()
        {
            add_action('wp_footer', array(__CLASS__, 'site_grey'));
        }
        //网站变灰
        public static function site_grey()
        {

            echo '<style type="text/css">
             /*网站整体灰白 - Npcink*/
             html {
                 -webkit-filter: grayscale(0.95); /* webkit */
                 -moz-filter: grayscale(0.95); /*firefox*/
                 -ms-filter: grayscale(0.95); /*ie9*/
                 -o-filter: grayscale(0.95); /*opera*/
                 filter: grayscale(0.95);
             }
             </>';
        }
    }
}
