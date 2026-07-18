<?php

defined('ABSPATH') || exit;

return sprintf(
    '<!-- wp:group {"style":{"border":{"color":"#dcdcde","radius":"16px","width":"1px"},"spacing":{"padding":{"top":"28px","right":"28px","bottom":"28px","left":"28px"},"blockGap":"20px"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-base-background-color has-background" style="border-color:#dcdcde;border-width:1px;border-radius:16px;padding-top:28px;padding-right:28px;padding-bottom:28px;padding-left:28px"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"6px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontSize":"13px","fontStyle":"normal","fontWeight":"600","letterSpacing":"0.06em"}},"textColor":"contrast-2"} -->
<p class="has-contrast-2-color has-text-color" style="font-size:13px;font-style:normal;font-weight:600;letter-spacing:0.06em">%1$s</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"26px"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<h3 class="wp-block-heading" style="margin-top:0;margin-bottom:0;font-size:26px">%2$s</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"}},"textColor":"contrast-2"} -->
<p class="has-contrast-2-color has-text-color" style="font-size:14px">%3$s</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p>%4$s</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"10px"},"spacing":{"padding":{"left":"22px","right":"22px","top":"12px","bottom":"12px"}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#" style="border-radius:10px;padding-top:12px;padding-right:22px;padding-bottom:12px;padding-left:22px">%5$s</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
    esc_html__('免费资源', 'npcink-site-toolbox'),
    esc_html__('资源名称', 'npcink-site-toolbox'),
    esc_html__('PDF · 2.4 MB', 'npcink-site-toolbox'),
    esc_html__('在这里说明资源能解决什么问题，以及读者下载后会获得什么。', 'npcink-site-toolbox'),
    esc_html__('立即下载', 'npcink-site-toolbox')
);
