<?php

/**
 * 未登录隐藏指定分类或标签下的文章 - 提示
 */

if (!class_exists('Npcink_Page_Hide_Prompt')) {
    class Npcink_Page_Hide_Prompt
    {
        private static $id_array; //数组
        public static function run($array)
        {
            self::$id_array = $array;
            add_action('pre_get_posts', array(__CLASS__, 'exclude_posts_and_add_login_hint'));
        }
        public static function exclude_posts_and_add_login_hint($query)
        {
            if (!is_admin() && !is_user_logged_in() && $query->is_main_query()) {
                // 检查是否在标签页或分类页
                if ($query->is_tag() || $query->is_category()) {
                    // 获取当前标签或分类
                    $current_term = $query->get_queried_object();

                    // 检查是否为受限标签或分类
                    if ($current_term && in_array($current_term->term_id, self::$id_array)) {
                        // 排除受限标签或分类下的文章
                        $query->set('tax_query', array(
                            array(
                                'taxonomy' => $current_term->taxonomy,
                                'field'    => 'term_id',
                                'terms'    => $current_term->term_id,
                                'operator' => 'NOT IN',
                            ),
                        ));

                        // 添加JavaScript代码以弹出登录提示框
                        add_action('wp_footer', function () {
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
                        });
                    }
                }
            }
        }
    }
}
