<?php

defined('ABSPATH') || exit;

return sprintf(
    '<!-- wp:group {"style":{"border":{"left":{"color":"#3858e9","width":"5px"},"radius":"14px"},"color":{"background":"#f3f5ff"},"spacing":{"padding":{"top":"26px","right":"28px","bottom":"26px","left":"28px"},"blockGap":"14px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="border-radius:14px;border-left-color:#3858e9;border-left-width:5px;background-color:#f3f5ff;padding-top:26px;padding-right:28px;padding-bottom:26px;padding-left:28px"><!-- wp:paragraph {"style":{"typography":{"fontSize":"13px","fontStyle":"normal","fontWeight":"700","letterSpacing":"0.08em"},"color":{"text":"#3858e9"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<p class="has-text-color" style="color:#3858e9;margin-top:0;margin-bottom:0;font-size:13px;font-style:normal;font-weight:700;letter-spacing:0.08em">%1$s</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"25px"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<h3 class="wp-block-heading" style="margin-top:0;margin-bottom:0;font-size:25px">%2$s</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>%3$s</p>
<!-- /wp:paragraph -->

<!-- wp:list {"style":{"spacing":{"padding":{"left":"22px"}}}} -->
<ul style="padding-left:22px"><!-- wp:list-item -->
<li>%4$s</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>%5$s</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>%6$s</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->',
    esc_html__('核心结论', 'npcink-site-toolbox'),
    esc_html__('读完这篇文章，你只需要记住这件事', 'npcink-site-toolbox'),
    esc_html__('用一两句话写出最重要的判断，让读者快速带走文章价值。', 'npcink-site-toolbox'),
    esc_html__('第一个关键要点', 'npcink-site-toolbox'),
    esc_html__('第二个关键要点', 'npcink-site-toolbox'),
    esc_html__('下一步可以采取的行动', 'npcink-site-toolbox')
);
