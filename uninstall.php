<?php
// 如果直接访问此文件，请中止。
defined('ABSPATH') || exit;

/**
 * 在卸载插件时激发。
 */

// 如果未从WordPress调用卸载，请退出。
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

global $wpdb;

// 删除自定义字段
delete_post_meta_by_key('mabox_trends_special');

// 删除插件的模块配置
delete_option('Magick_ToolBox_Option_Optimize');
delete_option('Magick_ToolBox_Option_Page');
delete_option('Magick_ToolBox_Option_Function');
delete_option('Magick_ToolBox_Option_H5');
delete_option('Magick_ToolBox_Option_Shortcode');
delete_option('Magick_ToolBox_Option_Template');
// 删除 2.2.0+ 新增模块选项键
delete_option('Magick_ToolBox_Option_Domestic');
delete_option('Magick_ToolBox_Option_Performance');

// 删除其他独立模块选项键
delete_option('Magick_ToolBox_Option_Services');
delete_option('Magick_ToolBox_Option_Feedback');

// 删除模块路由表选项
delete_option('Magick_ToolBox_Active_Modules');

// 删除 SEO 相关选项（可能由 seo_category_add_meat.php 创建）
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time uninstall cleanup; caching rows being deleted would be incorrect.
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like('cat-title-') . '%'));
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time uninstall cleanup; caching rows being deleted would be incorrect.
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like('cat-words-') . '%'));

// 删除缩略图切换器相关 transient
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time uninstall cleanup; caching rows being deleted would be incorrect.
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like('_transient_ts_') . '%'));
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time uninstall cleanup; caching rows being deleted would be incorrect.
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like('_transient_timeout_ts_') . '%'));

// 删除字数统计缓存
delete_transient('mabox_total_chars');

// 删除反馈与遥测数据
delete_option('mabox_telemetry_data');
delete_option('mabox_feedback_stats');
delete_option('mabox_feature_popularity');
delete_option('mabox_telemetry_user_count');

// 删除搜索日志
delete_option('mabox_search_log');

// 删除链接统计自定义表（如果存在）
$mabox_table_name = $wpdb->prefix . 'link_counter';
if (1 === preg_match('/\A[A-Za-z0-9_]+\z/', $mabox_table_name)) {
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- One-time uninstall schema cleanup; identifier is fixed-suffix and allowlist validated for WordPress 6.0 compatibility.
	$wpdb->query("DROP TABLE IF EXISTS `{$mabox_table_name}`");
}
