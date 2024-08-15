<?php

/**
 * 效果：偷瞄猫猫
 * 来源1：https://lishuma.com/connect
 * 来源2：https://www.shephe.com/website/
 */
if (!class_exists('Npcink_Page_Go_Top_Peep_Cat')) {
    class Npcink_Page_Go_Top_Peep_Cat
    {
        public static function run()
        {
            //偷瞄猫猫
            add_action('wp_footer', array(__CLASS__, 'peep_cat'), 100);
            //add_action('wp_enqueue_scripts', array(__CLASS__, 'peep_cat_js'));//纯jS方案
        }
        //偷瞄猫猫
        public static function peep_cat()
        {
            //准备图片地址
            $cat_url = plugin_dir_url(__FILE__) . 'cat.png';
?>
            <div id="topcontrol" onclick="goTop()">
                <img src="<?php echo $cat_url ?>" alt="偷瞄猫猫" title="偷瞄猫猫">
            </div>
            <script>
                const goTop = () => {
                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                }
                const topControl = document.getElementById('topcontrol');
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 600) {
                        topControl.classList.add('npcShow');
                    } else {
                        topControl.classList.remove('npcShow');
                    }
                });
            </script>
            <style>
                #topcontrol {
                    position: fixed;
                    bottom: 20px;
                    right: 0px;
                    /* 修正位置使其不贴边 */
                    opacity: 0;
                    /* 初始状态隐藏 */
                    transition: opacity 0.3s ease;
                    /* 动画效果 */
                    cursor: pointer;
                }

                #topcontrol.npcShow {
                    opacity: 1;
                    /* 滚动到一定高度后显示 */
                }
            </style>
<?php
        }


        //加载资源
        public static function peep_cat_js()
        {
            wp_enqueue_script(
                MAGICK_MIXTURE_NAME . '_go_top_cat',
                plugin_dir_url(__FILE__) . 'peep_cat/cat.js',
                array("jquery"),
                MAGICK_MIXTURE_VERSION,
                true
            );
        }
    }
}
