<?php
// 如果直接访问此文件，请中止。
defined('ABSPATH') || exit;
/**
 * 配置 Schema 定义类
 *
 * 负责：
 * 1. 定义每个模块、子模块、字段的类型、默认值、校验规则
 * 2. 提供 validate() 和 sanitize() 方法供保存时调用
 * 3. 提供 get_defaults() 供新安装或重置使用
 * 4. 提供 get_schema() 供 REST API 返回给前端
 *
 * @since 2.5.0
 */

if (!class_exists('MaBox_Config_Schema')) {
    class MaBox_Config_Schema {

        private static $schema = null;

        private static function build_schema() {
            return array(
                'optimize' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_OPTIMIZE,
                    'site' => array(
                        'hide_top_toolbar'       => array('type' => 'boolean', 'default' => false),
                        'no_escape'              => array('type' => 'boolean', 'default' => false),
                        'remove_RSS_version'     => array('type' => 'boolean', 'default' => false),
                        'renew'                 => array('type' => 'boolean', 'default' => false),
                        'category_link_simplify' => array('type' => 'boolean', 'default' => false),
                        'search_link_simplify'   => array('type' => 'boolean', 'default' => false),
                        'remove_sitemap_users'   => array('type' => 'boolean', 'default' => false),
                        'user_list_show_nickname' => array('type' => 'boolean', 'default' => false),
                        'cdn_replace'            => array('type' => 'boolean', 'default' => false),
                        'cdn_gravatar'           => array('type' => 'boolean', 'default' => false),
                        'cdn_gravatar_mirror'    => array('type' => 'string',  'default' => 'gravatar.loli.net/avatar/', 'sanitize' => 'esc_url_raw'),
                        'cdn_google_fonts'       => array('type' => 'boolean', 'default' => false),
                        'cdn_google_fonts_mirror' => array('type' => 'string',  'default' => 'fonts.loli.net', 'sanitize' => 'sanitize_text_field'),
                        'cdn_google_ajax'        => array('type' => 'boolean', 'default' => false),
                        'cdn_custom'             => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'hide_email_ip'          => array('type' => 'boolean', 'default' => false),
                    ),
                    'medium' => array(
                        'img_add_tag'     => array('type' => 'boolean', 'default' => false),
                        'no_auto_size'    => array('type' => 'boolean', 'default' => false),
                        'medium_add_svg'  => array('type' => 'boolean', 'default' => false),
                        'upload_auto_name' => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                    ),
                    'admin' => array(
                        'add_user'            => array('type' => 'boolean', 'default' => false),
                        'add_time'            => array('type' => 'boolean', 'default' => false),
                        'show_id'             => array('type' => 'boolean', 'default' => false),
                        'thumbnail_switcher'  => array('type' => 'boolean', 'default' => false),
                    ),
                ),
                'page' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_PAGE,
                    'comment' => array(
                        'comment_emote'              => array('type' => 'boolean', 'default' => false),
                        'interval'                   => array('type' => 'boolean', 'default' => false),
                        'interval_time'              => array('type' => 'number',  'default' => 5, 'min' => 1, 'max' => 3600),
                        'words_number'               => array('type' => 'boolean', 'default' => false),
                        'words_number_min'           => array('type' => 'number',  'default' => 0, 'min' => 0),
                        'words_number_max'           => array('type' => 'number',  'default' => 120, 'min' => 1),
                        'english'                    => array('type' => 'boolean', 'default' => false),
                        'only'                       => array('type' => 'boolean', 'default' => false),
                        'modify_comment_user'        => array('type' => 'boolean', 'default' => false),
                        'sensitive_words'            => array('type' => 'boolean', 'default' => false),
                        'sensitive_words_list'       => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'sensitive_words_action'     => array('type' => 'string',  'default' => 'replace', 'enum' => array('replace', 'block')),
                        'sensitive_words_replace_char' => array('type' => 'string',  'default' => '***', 'sanitize' => 'sanitize_text_field'),
                        'baidu_moderation'           => array('type' => 'boolean', 'default' => false),
                        'baidu_moderation_api_key'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'baidu_moderation_secret_key' => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'baidu_moderation_action'    => array('type' => 'string',  'default' => 'mark', 'enum' => array('block', 'mark')),
                    ),
                    'feature' => array(
                        'title'                    => array('type' => 'boolean', 'default' => false),
                        'title_front'              => array('type' => 'string',  'default' => '(/≧▽≦/)你又回来啦！', 'sanitize' => 'sanitize_text_field'),
                        'title_after'              => array('type' => 'string',  'default' => '你别走吖 Σ(っ °Д °;)っ', 'sanitize' => 'sanitize_text_field'),
                        'top_loading'              => array('type' => 'boolean', 'default' => false),
                        'particle'                 => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'scrol'                    => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'screen_hair'              => array('type' => 'boolean', 'default' => false),
                        'site_grey'                => array('type' => 'boolean', 'default' => false),
                        'lantern'                  => array('type' => 'boolean', 'default' => false),
                        'lantern_left'             => array('type' => 'string',  'default' => '春', 'sanitize' => 'sanitize_text_field'),
                        'lantern_right'            => array('type' => 'string',  'default' => '节', 'sanitize' => 'sanitize_text_field'),
                        'pixel_chicken'            => array('type' => 'boolean', 'default' => false),
                        'past_books'               => array('type' => 'boolean', 'default' => false),
                        'go_top'                   => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'page_back_top_cat_right'  => array('type' => 'number',  'default' => 60, 'min' => 0, 'max' => 200),
                        'copy_pop_up'              => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'bottom_effect'            => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'page_scrolling'           => array('type' => 'boolean', 'default' => false),
                        'background_effect'        => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'reading_progress'         => array('type' => 'boolean', 'default' => false),
                        'reading_progress_color'    => array('type' => 'string',  'default' => '#1677ff', 'sanitize' => 'sanitize_hex_color'),
                        'reading_progress_height'  => array('type' => 'number',  'default' => 3, 'min' => 1, 'max' => 20),
                        'font_switch'              => array('type' => 'boolean', 'default' => false),
                        'fonts'                    => array('type' => 'string',  'default' => 'Microsoft YaHei,Simsun,PingFang SC,Noto Sans SC', 'sanitize' => 'sanitize_text_field'),
                        'font_position'            => array('type' => 'string',  'default' => 'bottom-right', 'sanitize' => 'sanitize_text_field'),
                    ),
                    'function' => array(
                        'first_picture'           => array('type' => 'boolean', 'default' => false),
                        'add_inks'                => array('type' => 'boolean', 'default' => false),
                        'go_middle'               => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'remove_single_link'       => array('type' => 'boolean', 'default' => false),
                        'color_tag'                => array('type' => 'boolean', 'default' => false),
                        'add_last_update'         => array('type' => 'boolean', 'default' => false),
                        'no_login_img'            => array('type' => 'boolean', 'default' => false),
                        'maintenance_tips'        => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'countdown'               => array('type' => 'array',   'default' => array()),
                        'countdown_title'         => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'countdown_image'         => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'countdown_content'       => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'share'                   => array('type' => 'boolean', 'default' => false),
                        'share_position'          => array('type' => 'string',  'default' => 'right', 'sanitize' => 'sanitize_text_field'),
                        'share_top'               => array('type' => 'string',  'default' => '200', 'sanitize' => 'sanitize_text_field'),
                        'share_margins'           => array('type' => 'string',  'default' => '20', 'sanitize' => 'sanitize_text_field'),
                        'share_text'              => array('type' => 'string',  'default' => '发现一个蛮有意思的网站，分享给你看看 - ', 'sanitize' => 'sanitize_text_field'),
                        'share_email_email'       => array('type' => 'string',  'default' => 'test@npc.ink', 'sanitize' => 'sanitize_email'),
                        'share_email_title'       => array('type' => 'string',  'default' => '发现有趣的链接', 'sanitize' => 'sanitize_text_field'),
                        'share_email_content'     => array('type' => 'string',  'default' => '发现一个有趣的网站，分享给你看看', 'sanitize' => 'sanitize_textarea_field'),
                        'share_img_home'          => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'share_img_page'          => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'share_img_about'         => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'switch_lang_jf'          => array('type' => 'boolean', 'default' => false),
                        'default_thumbnail'       => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'search_limit'            => array('type' => 'boolean', 'default' => false),
                        'search_limit_count'      => array('type' => 'number',  'default' => 10, 'min' => 1, 'max' => 100),
                        'top_ad'                  => array('type' => 'boolean', 'default' => false),
                        'top_ad_content'          => array('type' => 'string',  'default' => '', 'sanitize' => 'wp_kses_post'),
                        'top_ad_position'         => array('type' => 'string',  'default' => 'before_header', 'sanitize' => 'sanitize_text_field'),
                        'batch_replace'           => array('type' => 'boolean', 'default' => false),
                        'batch_replace_pairs'     => array('type' => 'array',   'default' => array()),
                        'login_search'            => array('type' => 'boolean', 'default' => false),
                        'article_rating'          => array('type' => 'boolean', 'default' => false),
                        'header_notice'           => array('type' => 'boolean', 'default' => false),
                        'header_notice_text'      => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'header_notice_color'     => array('type' => 'string',  'default' => '#1677ff', 'sanitize' => 'sanitize_hex_color'),
                        'header_notice_link'      => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'header_notice_dismissible' => array('type' => 'boolean', 'default' => true),
                        'anti_crawler'            => array('type' => 'boolean', 'default' => false),
                        'anti_crawler_max_requests' => array('type' => 'number',  'default' => 60, 'min' => 1),
                        'anti_crawler_time_window' => array('type' => 'number',  'default' => 60, 'min' => 1),
                        'anti_crawler_tecent_id'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'anti_crawler_tecent_key'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'link_source'             => array('type' => 'boolean', 'default' => false),
                        'source_key'              => array('type' => 'string',  'default' => 'npc', 'sanitize' => 'sanitize_text_field'),
                        'ticket'                  => array('type' => 'boolean', 'default' => false),
                        'diary'                   => array('type' => 'boolean', 'default' => false),
                    ),
                    'jurisdiction' => array(
                        'ban_open_weixing'         => array('type' => 'boolean', 'default' => false),
                        'ban_open_weixing_mode'    => array('type' => 'string',  'default' => 'alert', 'enum' => array('alert', 'optimize'), 'sanitize' => 'sanitize_text_field'),
                        'wechat_guide_text'        => array('type' => 'string',  'default' => '点击右上角 ··· 在浏览器中打开', 'sanitize' => 'sanitize_text_field'),
                        'wechat_xcx_guide'        => array('type' => 'boolean', 'default' => false),
                        'wechat_xcx_guide_text'   => array('type' => 'string',  'default' => '在小程序中打开', 'sanitize' => 'sanitize_text_field'),
                        'wechat_xcx_link'         => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'ban_open_qq'             => array('type' => 'boolean', 'default' => false),
                        'front_debug'             => array('type' => 'boolean', 'default' => false),
                        'ban_copy'                => array('type' => 'boolean', 'default' => false),
                        'category_id'             => array('type' => 'array',   'default' => array()),
                        'tag_id'                  => array('type' => 'array',   'default' => array()),
                        'page_id'                => array('type' => 'array',   'default' => array()),
                        'single_id'              => array('type' => 'array',   'default' => array()),
                        'tip_content'             => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                    ),
                ),
                'function' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_FUNCTION,
                    'auxiliary' => array(
                        'single_count'       => array('type' => 'boolean', 'default' => false),
                        'no_malice_key'      => array('type' => 'boolean', 'default' => false),
                        'malice_keu_content' => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'baidu_tonji'        => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'google_tonji'       => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'biying_tonji'       => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'uniqueKey'         => array('type' => 'number',  'default' => 0),
                    ),
                    'b2' => array(
                        'add_order_menu' => array('type' => 'boolean', 'default' => false),
                        'b2_count'       => array('type' => 'boolean', 'default' => false),
                    ),
                    'wx_xcx' => array(
                        'active' => array('type' => 'boolean', 'default' => false),
                        'appid'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'secret' => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'site'   => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'path'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'query'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                    ),
                    'seo' => array(
                        'title'        => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'keywords'     => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'description'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'seo_single'   => array('type' => 'boolean', 'default' => false),
                        'seo_category' => array('type' => 'boolean', 'default' => false),
                    ),
                    'config' => array(
                        'pop_tips'      => array('type' => 'boolean', 'default' => false),
                        'tips_time'     => array('type' => 'number',  'default' => 0, 'min' => 0),
                        'tips_content'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'tips_button'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'tips_link'     => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                    ),
                ),
                'h5' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_H5,
                    'home' => array(
                        'switch'   => array('type' => 'boolean', 'default' => false),
                        'slide'    => array('type' => 'array',   'default' => array(1)),
                        'slide_all' => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'more'     => array('type' => 'number',  'default' => 1, 'min' => 0),
                    ),
                    'contact' => array(
                        'title'        => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'title_one'    => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'content_one'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'title_two'    => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'content_two'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'brand_link'   => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'brand_logo'   => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'introduce'    => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                    ),
                ),
                'login' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_LOGIN,
                    'beautify' => array(
                        'modify_login_link' => array('type' => 'boolean', 'default' => false),
                        'remove_langue'     => array('type' => 'boolean', 'default' => false),
                        'custom_login_page' => array('type' => 'boolean', 'default' => false),
                        'background_left'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_hex_color'),
                        'background_right'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_hex_color'),
                        'logo_size'         => array('type' => 'number',  'default' => 84, 'min' => 20, 'max' => 300),
                        'top_logo'          => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'background_img'    => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                    ),
                    'security' => array(
                        'login_code' => array('type' => 'string',  'default' => 'false', 'sanitize' => 'sanitize_text_field'),
                        'tecent_id'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'tecent_key' => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                    ),
                ),
                'shortcode' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_SHORTCODE,
                    'compose' => array(
                        'single_list'        => array('type' => 'boolean', 'default' => false),
                        'single_copy'        => array('type' => 'boolean', 'default' => false),
                        'runcode'            => array('type' => 'boolean', 'default' => false),
                        'bilibili'           => array('type' => 'boolean', 'default' => false),
                        'wx_unlock'          => array('type' => 'boolean', 'default' => false),
                        'wx_unlock_name'     => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'wx_unlock_qrcode'   => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'wx_unlock_codes'    => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'wx_unlock_tip'      => array('type' => 'string',  'default' => '关注公众号获取验证码', 'sanitize' => 'sanitize_text_field'),
                        'wx_unlock_keyword_tip' => array('type' => 'string',  'default' => '关注公众号，发送关键词获取验证码', 'sanitize' => 'sanitize_text_field'),
                        'reward'             => array('type' => 'boolean', 'default' => false),
                        'reward_wx_qr'       => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'reward_ali_qr'      => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'reward_title'       => array('type' => 'string',  'default' => '感谢您的支持', 'sanitize' => 'sanitize_text_field'),
                        'reward_wx_text'     => array('type' => 'string',  'default' => '微信', 'sanitize' => 'sanitize_text_field'),
                        'reward_ali_text'    => array('type' => 'string',  'default' => '支付宝', 'sanitize' => 'sanitize_text_field'),
                        'reward_btn_text'    => array('type' => 'string',  'default' => '打赏', 'sanitize' => 'sanitize_text_field'),
                    ),
                    'pendant' => array(
                        'merc_map'      => array('type' => 'boolean', 'default' => false),
                        'merc_location' => array('type' => 'array',  'default' => array()),
                    ),
                ),
                'template' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_TEMPLATE,
                    'static' => array(
                        'triangle' => array('type' => 'boolean', 'default' => false),
                    ),
                    'trends' => array(
                        'special' => array('type' => 'boolean', 'default' => false),
                    ),
                ),
                'domestic' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_DOMESTIC,
                    'compliance' => array(
                        'icp_enabled'    => array('type' => 'boolean', 'default' => false),
                        'icp_number'     => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'icp_link'       => array('type' => 'string',  'default' => 'https://beian.miit.gov.cn/', 'sanitize' => 'esc_url_raw'),
                        'police_enabled' => array('type' => 'boolean', 'default' => false),
                        'police_number'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'police_link'    => array('type' => 'string',  'default' => 'https://www.beian.gov.cn/portal/registerSystemInfo', 'sanitize' => 'esc_url_raw'),
                        'cookie_enabled' => array('type' => 'boolean', 'default' => false),
                        'cookie_style'   => array('type' => 'string',  'default' => 'bottom', 'enum' => array('bottom', 'top', 'center'), 'sanitize' => 'sanitize_text_field'),
                        'cookie_title'   => array('type' => 'string',  'default' => 'Cookie 同意', 'sanitize' => 'sanitize_text_field'),
                        'cookie_content' => array('type' => 'string',  'default' => '本网站使用 Cookie 来改善您的体验。继续浏览即表示您同意我们的 Cookie 政策。', 'sanitize' => 'sanitize_textarea_field'),
                        'cookie_button'  => array('type' => 'string',  'default' => '我知道了', 'sanitize' => 'sanitize_text_field'),
                        'copyright_enabled' => array('type' => 'boolean', 'default' => false),
                        'copyright_html' => array('type' => 'string',  'default' => '', 'sanitize' => 'wp_kses_post'),
                    ),
                    'baidu_push' => array(
                        'active_push_enabled' => array('type' => 'boolean', 'default' => false),
                        'site'                => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'token'               => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'auto_push_enabled'   => array('type' => 'boolean', 'default' => false),
                        'batch_push_enabled'   => array('type' => 'boolean', 'default' => false),
                    ),
                    'wechat' => array(
                        'jssdk_enabled'          => array('type' => 'boolean', 'default' => false),
                        'appid'                  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'appsecret'              => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'guide_overlay_enabled'  => array('type' => 'boolean', 'default' => false),
                        'guide_mode'             => array('type' => 'string',  'default' => 'guide', 'sanitize' => 'sanitize_text_field'),
                        'guide_text'             => array('type' => 'string',  'default' => '点击右上角 ··· 在浏览器中打开', 'sanitize' => 'sanitize_text_field'),
                        'guide_qrcode'           => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                    ),
                    'comment_security' => array(
                        'blacklist_enabled'         => array('type' => 'boolean', 'default' => false),
                        'blacklist_words'           => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'blacklist_action'          => array('type' => 'string',  'default' => 'block', 'enum' => array('block', 'mark'), 'sanitize' => 'sanitize_text_field'),
                        'link_limit_enabled'        => array('type' => 'boolean', 'default' => false),
                        'link_limit_count'          => array('type' => 'number',  'default' => 2, 'min' => 0),
                        'nickname_filter_enabled'   => array('type' => 'boolean', 'default' => false),
                        'nickname_filter_words'     => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                        'email_domain_enabled'      => array('type' => 'boolean', 'default' => false),
                        'email_domain_blacklist'   => array('type' => 'string',  'default' => '10minutemail.com,guerrillamail.com,temp-mail.org', 'sanitize' => 'sanitize_textarea_field'),
                        'duplicate_enabled'         => array('type' => 'boolean', 'default' => false),
                        'ip_rate_enabled'           => array('type' => 'boolean', 'default' => false),
                        'ip_rate_limit'             => array('type' => 'number',  'default' => 5, 'min' => 1),
                        'ip_rate_window'            => array('type' => 'number',  'default' => 60, 'min' => 1),
                        'log_enabled'               => array('type' => 'boolean', 'default' => false),
                    ),
                    'login_security' => array(
                        'fail_limit_enabled'    => array('type' => 'boolean', 'default' => false),
                        'fail_limit_count'      => array('type' => 'number',  'default' => 5, 'min' => 1),
                        'fail_lock_duration'    => array('type' => 'number',  'default' => 30, 'min' => 1),
                        'ip_lock_enabled'       => array('type' => 'boolean', 'default' => false),
                        'ip_lock_count'         => array('type' => 'number',  'default' => 10, 'min' => 1),
                        'ip_lock_duration'      => array('type' => 'number',  'default' => 60, 'min' => 1),
                        'custom_login_enabled'  => array('type' => 'boolean', 'default' => false),
                        'custom_login_slug'     => array('type' => 'string',  'default' => 'my-login', 'sanitize' => 'sanitize_title'),
                        'ban_enumeration_enabled' => array('type' => 'boolean', 'default' => false),
                        'login_notify_enabled'  => array('type' => 'boolean', 'default' => false),
                        'login_log_enabled'     => array('type' => 'boolean', 'default' => false),
                        'ip_whitelist_enabled'  => array('type' => 'boolean', 'default' => false),
                        'ip_whitelist'          => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                    ),
                ),
                'performance' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_PERFORMANCE,
                    'oss' => array(
                        'enabled'      => array('type' => 'boolean', 'default' => false),
                        'provider'     => array('type' => 'string',  'default' => 'aliyun', 'sanitize' => 'sanitize_text_field'),
                        'access_key'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'secret_key'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'bucket'       => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'region'       => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                        'domain'       => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                        'delete_local' => array('type' => 'boolean', 'default' => false),
                    ),
                    'seo_checker' => array(
                        'enabled' => array('type' => 'boolean', 'default' => false),
                    ),
                    'media_health' => array(
                        'enabled' => array('type' => 'boolean', 'default' => false),
                    ),
                    'search_enhance' => array(
                        'highlight_enabled' => array('type' => 'boolean', 'default' => false),
                        'recommend_enabled' => array('type' => 'boolean', 'default' => false),
                        'hotwords_enabled'  => array('type' => 'boolean', 'default' => false),
                    ),
                    'db_clean' => array(
                        'enabled'             => array('type' => 'boolean', 'default' => false),
                        'clean_revisions'    => array('type' => 'boolean', 'default' => false),
                        'clean_drafts'       => array('type' => 'boolean', 'default' => false),
                        'clean_spam_comments' => array('type' => 'boolean', 'default' => false),
                        'clean_transients'   => array('type' => 'boolean', 'default' => false),
                        'auto_clean'         => array('type' => 'boolean', 'default' => false),
                        'auto_clean_schedule' => array('type' => 'string',  'default' => 'weekly', 'enum' => array('daily', 'weekly', 'monthly'), 'sanitize' => 'sanitize_text_field'),
                    ),
                ),
                'ai_review' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_AI_REVIEW,
                    '_flat' => true,
                    'enabled'              => array('type' => 'boolean', 'default' => false),
                    'provider'            => array('type' => 'string',  'default' => 'local', 'sanitize' => 'sanitize_text_field'),
                    'mode'                => array('type' => 'string',  'default' => 'mark', 'enum' => array('mark', 'block'), 'sanitize' => 'sanitize_text_field'),
                    'deepseek_api_key'    => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                    'deepseek_api_url'    => array('type' => 'string',  'default' => 'https://api.deepseek.com/v1/chat/completions', 'sanitize' => 'esc_url_raw'),
                    'deepseek_model'      => array('type' => 'string',  'default' => 'deepseek-chat', 'sanitize' => 'sanitize_text_field'),
                    'aliyun_access_key'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                    'aliyun_secret_key'   => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                    'aliyun_region'       => array('type' => 'string',  'default' => 'cn-shanghai', 'sanitize' => 'sanitize_text_field'),
                    'custom_api_url'      => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                    'custom_api_method'   => array('type' => 'string',  'default' => 'POST', 'sanitize' => 'sanitize_text_field'),
                    'custom_api_headers'  => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                    'custom_api_body_template' => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                    'local_rules_enabled' => array('type' => 'boolean', 'default' => false),
                    'local_keywords'      => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                    'local_regex'         => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_textarea_field'),
                    'strict_mode'         => array('type' => 'boolean', 'default' => false),
                    'log_enabled'         => array('type' => 'boolean', 'default' => false),
                    'log_max_entries'     => array('type' => 'number',  'default' => 500, 'min' => 10, 'max' => 10000),
                ),
                'services' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_SERVICES,
                    '_flat' => true,
                    'enabled'              => array('type' => 'boolean', 'default' => false),
                    'wechat_qr'           => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                    'wechat_id'            => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_text_field'),
                    'email'               => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_email'),
                    'website'             => array('type' => 'string',  'default' => '', 'sanitize' => 'esc_url_raw'),
                    'service_custom_dev'  => array('type' => 'boolean', 'default' => false),
                    'service_deployment'  => array('type' => 'boolean', 'default' => false),
                    'service_theme_adapt' => array('type' => 'boolean', 'default' => false),
                    'service_support'     => array('type' => 'boolean', 'default' => false),
                    'cases'               => array('type' => 'array',   'default' => array()),
                ),
                'feedback' => array(
                    '_option_key' => MAGICK_MIXTURE_OPTION_FEEDBACK,
                    '_flat' => true,
                    'enabled'              => array('type' => 'boolean', 'default' => false),
                    'feedback_enabled'     => array('type' => 'boolean', 'default' => false),
                    'feedback_email'       => array('type' => 'string',  'default' => '', 'sanitize' => 'sanitize_email'),
                    'feedback_auto_reply'  => array('type' => 'string',  'default' => '感谢您的反馈，我们会尽快处理。', 'sanitize' => 'sanitize_textarea_field'),
                    'telemetry_enabled'    => array('type' => 'boolean', 'default' => false),
                    'telemetry_anonymous' => array('type' => 'boolean', 'default' => false),
                    'show_insights'        => array('type' => 'boolean', 'default' => false),
                ),
            );
        }

        /**
         * 获取完整 schema
         */
        public static function get_schema() {
            if (self::$schema === null) {
                self::$schema = self::build_schema();
            }
            return self::$schema;
        }

        /**
         * 获取所有模块的默认值
         *
         * @return array
         */
        public static function get_defaults() {
            $schema = self::get_schema();
            $defaults = array();

            foreach ($schema as $module_key => $module_def) {
                if ($module_key === '_option_key' || $module_key === '_flat') {
                    continue;
                }

                if (!empty($module_def['_flat'])) {
                    $defaults[$module_key] = array();
                    foreach ($module_def as $field_key => $field_def) {
                        if ($field_key === '_option_key' || $field_key === '_flat') {
                            continue;
                        }
                        $defaults[$module_key][$field_key] = $field_def['default'];
                    }
                } else {
                    $defaults[$module_key] = array();
                    foreach ($module_def as $sub_key => $sub_def) {
                        if ($sub_key === '_option_key' || $sub_key === '_flat') {
                            continue;
                        }
                        $defaults[$module_key][$sub_key] = array();
                        foreach ($sub_def as $field_key => $field_def) {
                            if ($field_key === '_option_key' || $field_key === '_flat') {
                                continue;
                            }
                            $defaults[$module_key][$sub_key][$field_key] = $field_def['default'];
                        }
                    }
                }
            }

            return $defaults;
        }

        /**
         * 校验并清洗单个字段值
         *
         * @param mixed  $value     原始值
         * @param array  $field_def 字段定义
         * @return array ['valid' => bool, 'value' => mixed, 'error' => string|null]
         */
        private static function sanitize_field($value, $field_def) {
            $type = isset($field_def['type']) ? $field_def['type'] : 'string';

            if ($value === null) {
                return array('valid' => true, 'value' => $field_def['default'], 'error' => null);
            }

            switch ($type) {
                case 'boolean':
                    $sanitized = rest_sanitize_boolean($value);
                    return array('valid' => true, 'value' => $sanitized, 'error' => null);

                case 'number':
                    $sanitized = is_numeric($value) ? floatval($value) : $field_def['default'];
                    if (isset($field_def['min']) && $sanitized < $field_def['min']) {
                        $sanitized = $field_def['min'];
                    }
                    if (isset($field_def['max']) && $sanitized > $field_def['max']) {
                        $sanitized = $field_def['max'];
                    }
                    return array('valid' => true, 'value' => $sanitized, 'error' => null);

                case 'string':
                    if (!is_string($value) && !is_numeric($value)) {
                        return array('valid' => false, 'value' => $field_def['default'], 'error' => 'Expected string');
                    }
                    $sanitized = (string) $value;
                    if (!empty($field_def['enum']) && !in_array($sanitized, $field_def['enum'], true)) {
                        $sanitized = $field_def['default'];
                    }
                    $sanitize_fn = !empty($field_def['sanitize']) ? $field_def['sanitize'] : 'sanitize_text_field';
                    if (is_callable($sanitize_fn)) {
                        $sanitized = call_user_func($sanitize_fn, $sanitized);
                    }
                    return array('valid' => true, 'value' => $sanitized, 'error' => null);

                case 'array':
                    if (!is_array($value)) {
                        return array('valid' => true, 'value' => $field_def['default'], 'error' => null);
                    }
                    return array('valid' => true, 'value' => $value, 'error' => null);

                default:
                    return array('valid' => true, 'value' => $value, 'error' => null);
            }
        }

        /**
         * 校验并清洗整个模块配置
         *
         * @param string $module 模块名
         * @param array  $data   模块数据
         * @return array ['valid' => bool, 'data' => array, 'errors' => array]
         */
        public static function validate_module($module, $data) {
            $schema = self::get_schema();

            if (!isset($schema[$module])) {
                return array('valid' => false, 'data' => array(), 'errors' => array("Unknown module: {$module}"));
            }

            $module_def = $schema[$module];
            $cleaned = array();
            $errors = array();

            if (!empty($module_def['_flat'])) {
                foreach ($module_def as $field_key => $field_def) {
                    if ($field_key === '_option_key' || $field_key === '_flat') {
                        continue;
                    }
                    $raw = isset($data[$field_key]) ? $data[$field_key] : null;
                    $result = self::sanitize_field($raw, $field_def);
                    $cleaned[$field_key] = $result['value'];
                    if ($result['error']) {
                        $errors[$module . '.' . $field_key] = $result['error'];
                    }
                }
            } else {
                foreach ($module_def as $sub_key => $sub_def) {
                    if ($sub_key === '_option_key' || $sub_key === '_flat') {
                        continue;
                    }
                    $cleaned[$sub_key] = array();
                    $sub_data = isset($data[$sub_key]) && is_array($data[$sub_key]) ? $data[$sub_key] : array();
                    foreach ($sub_def as $field_key => $field_def) {
                        if ($field_key === '_option_key' || $field_key === '_flat') {
                            continue;
                        }
                        $raw = isset($sub_data[$field_key]) ? $sub_data[$field_key] : null;
                        $result = self::sanitize_field($raw, $field_def);
                        $cleaned[$sub_key][$field_key] = $result['value'];
                        if ($result['error']) {
                            $errors[$module . '.' . $sub_key . '.' . $field_key] = $result['error'];
                        }
                    }
                }
            }

            return array(
                'valid'  => empty($errors),
                'data'   => $cleaned,
                'errors' => $errors,
            );
        }

        /**
         * 校验并清洗完整配置
         *
         * @param array $full_config 完整配置
         * @return array ['valid' => bool, 'data' => array, 'errors' => array]
         */
        public static function validate_full_config($full_config) {
            $schema = self::get_schema();
            $cleaned = array();
            $all_errors = array();

            foreach ($schema as $module_key => $module_def) {
                if ($module_key === '_option_key' || $module_key === '_flat') {
                    continue;
                }
                $module_data = isset($full_config[$module_key]) && is_array($full_config[$module_key]) ? $full_config[$module_key] : array();
                $result = self::validate_module($module_key, $module_data);
                $cleaned[$module_key] = $result['data'];
                if (!empty($result['errors'])) {
                    $all_errors = array_merge($all_errors, $result['errors']);
                }
            }

            return array(
                'valid'  => empty($all_errors),
                'data'   => $cleaned,
                'errors' => $all_errors,
            );
        }
    }
}