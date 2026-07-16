<?php

defined('ABSPATH') || exit;

/*
 暂停页模版
 */

include plugin_dir_path((__FILE__)) . 'index.php'; // 获取数据

wp_enqueue_style(
    'mabox-maintenance-countdown',
    $file_url . 'countdown/style.css',
    array(),
    MAGICK_MIXTURE_VERSION
);
wp_enqueue_script(
    'mabox-maintenance-countdown-script',
    $file_url . 'countdown/main.js',
    array(),
    MAGICK_MIXTURE_VERSION,
    false
);
wp_add_inline_script(
    'mabox-maintenance-countdown-script',
    'var targetDate = new Date(' . wp_json_encode((string) $countdown) . ');',
    'before'
);
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo esc_html($page_title); ?></title>
    <?php wp_print_styles(array('mabox-maintenance-responsive', 'mabox-maintenance-countdown')); ?>
    <?php wp_print_scripts('mabox-maintenance-countdown-script'); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <body>


        <!--复制开始-->
        <!--
	版本：1.0
	整理：Muze
	帮助：https://www.npc.ink
-->




        <h2 class="n-title main">
            <?php echo esc_html($countdown_title);
            ?>
        </h2>

        <div class="box">
            <p class="n-meat main">
                <?php echo wp_kses_post($countdown_content); ?>
            </p>
            <!--
            <p class="n-description main"> </p>-->


            <!--倒计时开始-->
            <div class="boxs">
                <?php include 'countdown/index.php'; ?>
            </div>
            <style>
                .box {
                    color: #fff;
                    text-align: center;
                }

                .boxs {
                    display: flex;
                    justify-content: center;
                    align-items: center;

                    color: #fff;
                }
            </style>
            <!--倒计时结束-->




        </div>

        <style type="text/css">
            body {
                background-color: #b52424 !important;
                margin-top: 10vh;
                padding: 0 10vw;

            }

            .box {
                margin-top: 5vh;
            }

            .main {
                text-align: center;
                padding-top: 10px;
                color: #fff;
                letter-spacing: 20px;
            }

            .n-title {
                font-size: 4em;
                margin-bottom: 5px;
            }

            .n-meat {
                font-size: 2em;
            }

            .n-description {
                line-height: 2em;
                margin-top: 150px;
            }

            .n-description span {
                font-size: 32px;
                font-weight: bold;
            }

            /**倒计时 */
            .countdown-desc {
                color: #fff;
            }
        </style>


    </body>

</html>
