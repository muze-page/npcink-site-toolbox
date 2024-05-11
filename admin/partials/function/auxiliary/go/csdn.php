<?php
/*
 Go中间页跳转 - 演示
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
.content {
  padding-top: 220px;
  width: 450px;
  margin: auto;
  word-break: break-all;
}
.content .logo-img img {
  display: block;
  width: 175px;
  height: 48px;
  margin: auto;
  margin-bottom: 16px;
}
.content .loading-item {
  background: #fff;
  padding: 24px;
  border-radius: 12px;
  border: 1px solid #e1e1e1;
}
.content .loading-tip {
  padding: 12px;
  margin-bottom: 16px;
  border-radius: 4px;
}
.content .tip2 {
  background: #fdf5e6;
}
.content .flex {
  display: flex;
  align-items: center;
}
.content .loading-img {
  width: 24px;
  height: 24px;
}
.content .loading-text {
  font-size: 16px;
  font-weight: 600;
  color: #222226;
  line-height: 22px;
  margin-left: 12px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.content .loading-topic {
  font-size: 14px;
  color: #222226;
  line-height: 24px;
  margin-bottom: 24px;
}
.content .loading-color2 {
  color: #fc5531;
}
.content .flex-end {
  display: flex;
  justify-content: flex-end;
  align-items: center;
}
.content .loading-btn {
  white-space: nowrap;
  font-size: 14px;
  color: #fc5531;
  border: 1px solid #fc5531;
  display: inline-block;
  box-sizing: border-box;
  padding: 6px 18px;
  border-radius: 18px;
  margin-left: 8px;
}
@media (max-width: 450px) {
  .content {
    padding-top: 120px;
    width: 94%;
  }
}
    </style>
</head>

<body>
    <div id="linkPage" class="link-page">
        <div class="content">
            <div class="loading-item ">
                <div class="flex loading-tip tip2">
                    <!--
                     <img class="loading-img" src="https://csdnimg.cn/release/link/img/warning20201108.png" alt="">
                   -->
                    <div class="loading-img">⚠️</div>
                    <div class="loading-text">请注意您的账号和财产安全</div>
                </div>
                <div class="loading-topic"><span>您即将离开<?php echo $site_name ?>，去往：</span>
                    <a class="loading-color2"><?php echo esc_url($external_url); ?></a>
                </div>
                <div class="flex-end">
                    <a class="loading-btn"  href="<?php echo esc_url($external_url); ?>" target="_self">继续</a>
                </div>
            </div>
        </div>

    </div>
</body>

</html>