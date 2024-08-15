<?php

/**
 * 效果：返回顶部
 * 平滑箭头：https://www.shephe.com/website/
 */
if (!class_exists('Npcink_Page_Go_Top_Smooth_Arrow')) {
    class Npcink_Page_Go_Top_Smooth_Arrow
    {
        public static function run()
        {
            add_action('wp_footer', array(__CLASS__, 'smooth_arrow'), 100);
        }
        //平滑箭头
        public static function smooth_arrow()
        {
?>
            <div class="grve-back-top">
                <div class="grve-arrow-wrapper" onclick="goTop()">
                    <svg width="16px" height="40px" viewBox="0 0 16 40">
                        <polygon class="grve-arrow-point" fill-rule="nonzero" points="8 0 14.75 6.60691267 13.3267423 8 8 2.78694936 2.67325773 8 1.25 6.60691267"></polygon>
                        <polygon class="grve-arrow-line" points="7 2 9 2 9 40 7 40"></polygon>
                    </svg>
                </div>
            </div>
            <script>
                const goTop = () => {
                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                }
            </script>
            <style>
                .grve-back-top {
                    position: fixed;
                    width: 2.5rem;
                    height: 2.5rem;
                    right: 1.5rem;
                    bottom: 2rem;
                    text-align: center;
                    z-index: 900;
                    overflow: hidden;
                    pointer-events: none;
                    -webkit-backface-visibility: hidden;
                    -moz-backface-visibility: hidden;
                    -ms-backface-visibility: hidden;


                }

                .grve-back-top .grve-arrow-wrapper {
                    height: inherit;
                    width: inherit;
                    cursor: pointer;
                    position: relative;
                    transition: all .3s ease;
                    transform: translateY(30%);

                    pointer-events: visible;
                }

                .grve-back-top .grve-arrow-wrapper:hover {
                    transform: translateY(0);
                }
            </style>
<?php
        }
    }
}
