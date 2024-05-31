<?php

/**
 * 未登录隐藏指定分类或标签下的文章 - 提示
 */

if (!class_exists('Npcink_Page_Hide_Prompt')) {
    class Npcink_Page_Hide_Prompt
    {
        private static $category_array; //分类数组
        private static $tag_array; //标签数组
        public static function run($category, $tag)
        {
            self::$category_array = $category;
            self::$tag_array = $tag;

            add_action('pre_get_posts', array(__CLASS__, 'add_login_hint_to_restricted_content'));
            // add_action('wp_footer', array(__CLASS__, 'show_login_prompt')); //弹窗提示
        }
        public static function add_login_hint_to_restricted_content($query)
        {
            if (!is_user_logged_in() && $query->is_main_query() && (is_category() || is_tag() || is_single())) {
                // 获取当前文章或页面的分类和标签的ID
                $categories = null;
                $tags = null;

                if (is_category()) {
                    $categories = $query->get_queried_object_id();
                } elseif (is_tag()) {
                    $tags = $query->get_queried_object_id();
                } elseif (is_single()) {
                    $post_categories = get_the_category();
                    $post_tags = get_the_tags();
                    if ($post_categories) {
                        $categories = array();
                        foreach ($post_categories as $category) {
                            $categories[] = $category->term_id;
                        }
                    }
                    if ($post_tags) {
                        $tags = array();
                        foreach ($post_tags as $tag) {
                            $tags[] = $tag->term_id;
                        }
                    }
                }

                // 定义受限的分类和标签的ID
                $restricted_category_ids = self::$category_array; // 例如：array( 1, 2 );
                $restricted_tag_ids = self::$tag_array; // 例如：array( 3, 4 );

                // 检查当前文章或页面是否属于受限的分类或标签
                $is_restricted = false;
                if ($categories) {
                    if (in_array($categories, $restricted_category_ids)) {
                        $is_restricted = true;
                    }
                }
                if ($tags && !$is_restricted) {
                    foreach ($tags as $tag) {
                        if (in_array($tag, $restricted_tag_ids)) {
                            $is_restricted = true;
                            break;
                        }
                    }
                }

                // 如果当前文章或页面属于受限的分类或标签，则显示登录提示
                if ($is_restricted) {
?>
                    <div class="login-hint">
                        抱歉，您没有权限访问此内容，请<a href="<?php echo wp_login_url(get_permalink()); ?>">登录</a>后访问。
                    </div>
            <?php
                }
            }
        }

        //提示内容
        public static function show_login_prompt()
        {
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var loginHintModal = document.createElement('div');
                    loginHintModal.innerHTML = '<div class="login-hint">抱歉，您没有权限访问此文章，请<a href="<?php echo wp_login_url(get_permalink()); ?>">登录</a>后访问。</div>';
                    loginHintModal.style.position = 'fixed';
                    loginHintModal.style.top = '50%';
                    loginHintModal.style.left = '50%';
                    loginHintModal.style.transform = 'translate(-50%, -50%)';
                    loginHintModal.style.background = '#fff';
                    loginHintModal.style.padding = '20px';
                    loginHintModal.style.border = '1px solid #ccc';
                    loginHintModal.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.1)';
                    loginHintModal.style.zIndex = '9999';
                    loginHintModal.style.textAlign = 'center';
                    loginHintModal.style.borderRadius = '8px';
                    document.body.appendChild(loginHintModal);
                });
            </script>
<?php
        }
    }
}
