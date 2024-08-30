<?php
/*
 暂停页模版 - 炫彩时钟 Autumn Pro
 */

include plugin_dir_path((__FILE__)) . '../index.php'; // 获取数据
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $page_title; ?></title>
</head>

<body class="white-font" <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <body>
        <link href="<?php echo $file_url . "rotate/style.css" ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo $file_url . "rotate/main.js" ?>"></script>
        <script type="text/javascript">
            //var countDownDate = new Date("2024/07/02 02:00").getTime();
            // 目标日期和时间
            var countDownDate = new Date("<?php echo $countdown ?>"); //规定以T分隔日期和时间
        </script>


        <div class="hidden_overflow gradient_violet">
            <div class="container">
                <div class="count-block">
                    <div class="head-area">

                        <!--
                        <a href="<?php echo home_url(); ?>" class="logo mob_logo">
                            <img src="<?php echo $favicon_url; ?>" alt="">
                        </a>
-->
                        <h2 class="time-left-txt"><?php echo $countdown_title ?></h2>
                    </div>
                    <div class="middle-area">
                        <div class="countdown-row">
                            <a href="#" class="logo">
                                <img src="" alt=""></a>
                            <div class="counting-row">
                                <div class="slot-type">
                                    <span class="num" id="day">00</span>
                                    <span class="param">天</span>
                                </div>
                                <div class="slot-type">
                                    <span class="num" id="hour">00</span>
                                    <span class="param">小时</span>
                                </div>
                                <div class="slot-type">
                                    <span class="num" id="min">00</span>
                                    <span class="param">分钟</span>
                                </div>
                                <div class="slot-type">
                                    <div class="num _INVISIBLE_" id="second">00</div>
                                    <span class="param">秒</span>
                                </div>
                            </div>
                            <div class="seconds-holder">
                                <div class="circle-holder">
                                    <div class="dark_digit IE_HIDE">
                                        <img src="<?php echo $file_url ?>/rotate/img/secondwhite.svg" class="round" alt="">
                                    </div>
                                    <svg class="dark_digit" width="100%" height="100%">
                                        <g id="clipPath">
                                            <image xlink:href="secondwhite.svg" width="100%" height="100%" transform="" class="round" id="digitalsecond" alt="">
                                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="10s" repeatCount="indefinite" />
                                            </image>
                                        </g>
                                        <defs>
                                            <clipPath id="hero-clip">
                                                <rect x="94%" y="47.2%" fill="#ff0000" width="110" height="64" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <div class="down_opacity_circle">
                                        <img src="<?php echo $file_url ?>/rotate/img/secondtrans_.svg" class="round" id="digitalsecond" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="countdown-caption">
                            <?php echo $countdown_content ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .countdown-caption {
                color: #fff;
            }
        </style>
    </body>

</html>