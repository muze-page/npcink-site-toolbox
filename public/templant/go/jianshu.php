<?php
/*
 Go中间页跳转 - 简书
 */

include plugin_dir_path((__FILE__)) . 'index.php'; // 获取数据
?>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title><?php echo $site_name ?> - 安全中心</title>
    <link rel="shortcut icon" href="<?php echo $favicon_url ?>" type="image/x-icon">
    <style>
        a {
            text-decoration: none;
        }

        ._3zKaPtMyr3HfhDiMWyCbjX_0 {
            position: absolute;
            width: 620px;
            padding: 40px 0;
            border-radius: 6px;
            text-align: center;
            top: 88px;
            left: 50%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            background-color: #fff;
        }

        ._-hCAGG-DBGnLqDZezXfbr_0 {
            font-size: 22px;
            color: #2f2f2f;
        }

        ._3ynK7cIQE6ZYP-OGjNuW5P_0 {
            font-size: 16px;
            color: #888888;
            margin-top: 8px;
        }

        .vo0utWjxXmh0EJk1JpZEo_0 {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            width: 460px;
            margin: 12px auto 0;
            padding: 10px;
            border-radius: 4px;
            background: #fafafa;
            border: 1px solid #dddddd;
            zoom: 1;
        }

        .vo0utWjxXmh0EJk1JpZEo_0 ._2kSprqh0pEaoewQz3qpbVt_0 {
            -webkit-flex-shrink: 0;
            -ms-flex-negative: 0;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            line-height: 40px;
            font-size: 20px;
            background: #bcc6d8;
            text-align: center;
            border-radius: 2px;
        }

        .vo0utWjxXmh0EJk1JpZEo_0 ._2kSprqh0pEaoewQz3qpbVt_0 .iconfont {
            color: #f3f3f3;
        }

        .vo0utWjxXmh0EJk1JpZEo_0 ._2VEbEOHfDtVWiQAJxSIrVi_0 {
            font-size: 14px;
            color: #3194d0;
            margin-left: 10px;
            overflow: hidden;
            -o-text-overflow: ellipsis;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        ._2HKmCX5YkSpBY9XP4yY14K_0 {
            text-align: center;
            font-size: 0;
            margin-top: 24px;
        }

        ._3OuyzjzFBDdQwRGk08HXHz_0 {
            display: inline-block;
            width: 144px;
            height: 44px;
            line-height: 43px;
            border-radius: 22px;
            font-size: 14px;
            color: #ea725d;
            border: 1px solid #ea725d;
            cursor: pointer;
        }

        @media (max-width: 450px) {
            ._3zKaPtMyr3HfhDiMWyCbjX_0 {
                width: 94%;
            }

            .vo0utWjxXmh0EJk1JpZEo_0 {
                width: 90%;
            }

            .vo0utWjxXmh0EJk1JpZEo_0 ._2VEbEOHfDtVWiQAJxSIrVi_0 {
                word-break: break-all;
                white-space: normal;
            }

            ._3OuyzjzFBDdQwRGk08HXHz_0 {
                display: block;
                width: 100%;
                height: 44px;
                line-height: 43px;
                font-size: 14px;
                border-radius: 2px;
                cursor: pointer;
            }
        }
    </style>
</head>

<body>
    <div class="_3zKaPtMyr3HfhDiMWyCbjX_0"><!---->
        <div class="_-hCAGG-DBGnLqDZezXfbr_0">即将跳转到外部网站</div>
        <div class="_3ynK7cIQE6ZYP-OGjNuW5P_0">安全性未知，是否继续</div>
        <div class="vo0utWjxXmh0EJk1JpZEo_0">
            <div class="_2kSprqh0pEaoewQz3qpbVt_0"><i class="iconfont ic-PClink">🔗</i></div>
            <div title="<?php echo esc_url($external_url); ?>" class="_2VEbEOHfDtVWiQAJxSIrVi_0"><?php echo esc_url($external_url); ?></div>
        </div>
        <a href="<?php echo esc_url($external_url); ?>" target="_self">
            <div class="_2HKmCX5YkSpBY9XP4yY14K_0">
                <div class="_3OuyzjzFBDdQwRGk08HXHz_0">继续前往</div>
            </div>
        </a>

    </div>
</body>

</html>