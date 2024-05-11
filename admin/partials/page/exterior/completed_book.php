<?php

/**
 * 效果：已写完的书
 * 来源：
 */
if (!class_exists('Npcink_Page_Completed_Book')) {
    class Npcink_Page_Completed_Book
    {
        public static function run()
        {
            add_filter('wp_footer', array(__CLASS__, 'allwords'));
        }
        /**
         * 已写完的书
         * https://www.npc.ink/276901.html
         */
        public static function allwords()
        {
            global $wpdb;
            $chars = 0;
            $results = $wpdb->get_results("SELECT post_content FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type = 'post'");
            foreach ($results as $result) {
                $chars += mb_strlen(trim($result->post_content), 'UTF-8');
            }

            $books = [
                50000 => '埃克苏佩里的《小王子》',
                70000 => '鲁迅的《呐喊》',
                90000 => '林海音的《城南旧事》',
                100000 => '马克·吐温的《王子与乞丐》',
                110000 => '鲁迅的《彷徨》',
                120000 => '余华的《活着》',
                130000 => '曹禺的《雷雨》',
                140000 => '史铁生的《宿命的写作》',
                150000 => '伯内特的《秘密花园》',
                160000 => '曹禺的《日出》',
                170000 => '马克·吐温的《汤姆·索亚历险记》',
                180000 => '沈从文的《边城》',
                190000 => '亚米契斯的《爱的教育》',
                200000 => '巴金的《寒夜》',
                210000 => '东野圭吾的《解忧杂货店》',
                220000 => '莫泊桑的《一生》',
                230000 => '简·奥斯汀的《傲慢与偏见》',
                250000 => '钱钟书的《围城》',
                280000 => '张炜的《古船》',
                300000 => '茅盾的《子夜》',
                310000 => '阿来的《尘埃落定》',
                320000 => '艾米莉·勃朗特的《呼啸山庄》',
                340000 => '雨果的《巴黎圣母院》',
                350000 => '东野圭吾的《白夜行》',
                400000 => '我国著名的四大名著',
                1000000 => '列夫·托尔斯泰的《战争与和平》'
            ];

            foreach ($books as $numChars => $book) {
                if ($chars < $numChars) {
                    echo '<p class="mami_past_books">全站共 ' . $chars . ' 字，写完一本' . $book . '了！</p>';
                    return;
                }
            }

            //保底
            echo '<p class="mami_past_books">全站共 ' . $chars . ' 字，已写一本列夫·托尔斯泰的《战争与和平》了！</p>';
        }
    }
}
