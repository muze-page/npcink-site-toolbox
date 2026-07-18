<?php

defined('ABSPATH') || exit;

return sprintf(
    '<!-- wp:group {"style":{"border":{"top":{"color":"#dcdcde","width":"1px"}},"spacing":{"padding":{"top":"22px"},"blockGap":"10px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-top-color:#dcdcde;border-top-width:1px;padding-top:22px"><!-- wp:heading {"level":4,"style":{"typography":{"fontSize":"16px"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<h4 class="wp-block-heading" style="margin-top:0;margin-bottom:0;font-size:16px">%1$s</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"}},"textColor":"contrast-2"} -->
<p class="has-contrast-2-color has-text-color" style="font-size:14px"><strong>%2$s</strong>%3$s</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"}},"textColor":"contrast-2"} -->
<p class="has-contrast-2-color has-text-color" style="font-size:14px"><strong>%4$s</strong>%5$s</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"14px"}},"textColor":"contrast-2"} -->
<p class="has-contrast-2-color has-text-color" style="font-size:14px"><strong>%6$s</strong>%7$s</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
    esc_html__('来源与版权说明', 'npcink-site-toolbox'),
    esc_html__('资料来源：', 'npcink-site-toolbox'),
    esc_html__('请填写原始资料、采访对象或参考链接。', 'npcink-site-toolbox'),
    esc_html__('版权声明：', 'npcink-site-toolbox'),
    esc_html__('本文采用的授权方式及权利归属。', 'npcink-site-toolbox'),
    esc_html__('转载要求：', 'npcink-site-toolbox'),
    esc_html__('请注明作者、出处和原文链接；如需商业使用，请先获得许可。', 'npcink-site-toolbox')
);
