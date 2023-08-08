<?php
//优化 站点
if (!class_exists('MaMi_Optimize_Site')) {
    class MaMi_Optimize_Site
    {
        //加载
        public static function run($config)
        {

            //获取选项
            $option =  MaMi_Admin::get_config($config, 'site');

            //禁止网站title中的 “-” 被转义
            $no_escape = MaMi_Admin::get_config($option, 'no_escape');
            if ($no_escape) {
                add_filter('run_wptexturize', '__return_false');
            };

            //文章关键词自动添加内链链接代码
            $add_inks = MaMi_Admin::get_config($option, 'add_inks');
            if ($add_inks) {
                add_filter('the_content', array(__CLASS__, 'tag_link'), 1);
            }

            //登录页LOGO改为首页链接
            $modify_login_link = MaMi_Admin::get_config($option, 'modify_login_link');
            if ($modify_login_link) {
                add_filter('login_headerurl', array(__CLASS__, 'admin_logo_home'));
            }

            //移除登录页语言选择器
            $remove_langue = MaMi_Admin::get_config($option, 'remove_langue');
            //https://www.iowen.cn/yichuwordpress59dengluyemianzhongdeyuyanqiehuankuang/
            if ($remove_langue) {
                add_filter('login_display_language_dropdown', '__return_false');
            }
        }

        /* 作用：登录页LOGO改为首页链接
         * 来源：https://www.iowen.cn/chundaimameihuawordpressmorendengluye/
         * */
        public static function admin_logo_home()
        {
            return esc_url(home_url());
        }

        /*
         *作用：Wordpress文章关键词自动添加内链链接代码
         *效果：https://www.npc.ink/15286.html
         */
        //按长度排序
        public static function tag_sort($a, $b)
        {
            if ($a->name == $b->name) {
                return 0;
            }

            return (strlen($a->name) > strlen($b->name)) ? -1 : 1;
        }
        //改变标签关键字
        public static function tag_link($content)
        {
            //连接数量
            $match_num_from = 1; //一篇文章中同一个关键字少于多少不锚文本（这个直接填1就好了）
            $match_num_to = 3; //一篇文章中同一个关键字最多出现多少次锚文本（建议不超过1次）
            $posttags = get_the_tags();
            if ($posttags) {
                usort($posttags, array(__CLASS__, "tag_sort"));
                foreach ($posttags as $tag) {
                    $link = get_tag_link($tag->term_id);
                    $keyword = $tag->name;
                    //连接代码
                    $cleankeyword = stripslashes($keyword);
                    $url = "<strong><a href=\"$link\" title=\"" . str_replace('%s', addcslashes($cleankeyword, '$'), __('查看所有文章关于 %s')) . "\"";
                    $url .= 'target="_blank"';
                    $url .= ">" . addcslashes($cleankeyword, '$') . "</a></strong>";
                    $limit = rand($match_num_from, $match_num_to);
                    //不连接的代码
                    $ex_word = '';
                    $case = '';
                    $content = preg_replace('|(<a[^>]+>)(.*)(' . $ex_word . ')(.*)(</a[^>]*>)|U' . $case, '$1$2%&&&&&%$4$5', $content);
                    $content = preg_replace('|(<img)(.*?)(' . $ex_word . ')(.*?)(>)|U' . $case, '$1$2%&&&&&%$4$5', $content);
                    $cleankeyword = preg_quote($cleankeyword, '\'');
                    $regEx = '\'(?!((<.*?)|(<a.*?)))(' . $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
                    $content = preg_replace($regEx, $url, $content, $limit);
                    $content = str_replace('%&&&&&%', stripslashes($ex_word), $content);
                }
            }
            return $content;
        }
    }
}
