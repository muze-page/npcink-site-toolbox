<?php
/*
 Go中间页跳转 - CSDN
 */

include plugin_dir_path((__FILE__)) . 'index.php'; // 获取数据
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <title>敬请期待 - Npcink</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>

<body>
  <!--复制开始-->
  <!--
	版本：1.0
	整理：Muze
	帮助：https://www.npc.ink/17635.html
-->
  <!--载入CSS样式-->
  <link href="<?php echo $url_css . "purple.css" ?>" rel="stylesheet" type="text/css" />

  <style>
    .waitimg {
     
      background: url(<?php echo $url_image . '/popure/wait.png' ?>) center no-repeat;
     
    }

    @media screen and (min-width: 750px) {
      .waitimg {
        background: url(<?php echo $url_image . '/popure/wait1.png' ?>) center no-repeat;
      }
    }
  </style>
  <div class="main">
    <div class="waitimg">
      <div class="beian">鄂ICP备18019477号</div>
    </div>
  </div>



</body>

</html>