<?php
//简单SEO
if (!class_exists('Npcink_Easy_Seo')) {
    class Npcink_Easy_Seo
    {
        public static function run($option)
        {
            //静态或动态首页
            if (is_front_page()) {
                //翻页是第一页
                if (get_query_var('paged') < 2) {
                    //站点标题
                    $title = MaBox_Admin::get_config($option, 'title');
                    if ($title !== '' && $title !== false) {
                        require_once plugin_dir_path(__FILE__) . 'site_title.php'; //载入文件
                        Npcink_Seo_Site_Title::run($title);
                    }

                    //站点描述
                    $description = MaBox_Admin::get_config($option, 'description');
                    if ($description !== '' && $description !== false) {
                        require_once plugin_dir_path(__FILE__) . 'site_description.php'; //载入文件
                        Npcink_Seo_Site_Description::run($description);
                    }

                    //站点关键词
                    $keywords = MaBox_Admin::get_config($option, 'keywords');
                    if ($keywords !== '' && $keywords !== false) {
                        require_once plugin_dir_path(__FILE__) . 'site_keywords.php'; //载入文件
                        Npcink_Seo_Site_Keywords::run($keywords);
                    }
                }
            }
        }
        /**
         * <meta name='description' content='SEO 描述' />
         * <meta name='keywords' content='1,2222,3，5' />
         */
    }
}
