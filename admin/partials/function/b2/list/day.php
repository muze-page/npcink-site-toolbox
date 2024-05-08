<?php
//本月每天销售统计（一年内）
/**
 * 一年内的时间
 * 每天的销售额
 */
if (!class_exists('Npcink_B2_Shop_Day')) {
    class Npcink_B2_Shop_Day
    {
        public static function run()
        {
            return self::get_day_data();
        }

        /**
         * 商城订单、实物、已发货
         */
        public static function get_day_data()
        {
            // 连接WordPress数据库
            global $wpdb;

            //拿到数据表
            $table_name = $wpdb->prefix . 'zrz_order';

            // 获取当前时间
            $current_date = current_time('mysql');

            // 计算六个月前的日期
            $six_months_ago = date('Y-m-d H:i:s', strtotime('-1 months', strtotime($current_date)));

            $search = "SELECT DATE(order_date) AS order_date, SUM(order_total) AS total
                FROM $table_name
                WHERE order_type = 'gx'
                AND order_commodity = 1
                AND order_state = 'c'
                AND order_date >= %s
                GROUP BY DATE(order_date)
            ";
            // 构建查询语句
            $query = $wpdb->prepare($search, $six_months_ago);

            // 执行查询
            $results = $wpdb->get_results($query);

            // 构建对象数组
            $sales_data = array();
            foreach ($results as $result) {
                $sales_data[] = array(
                    'time' => $result->order_date, //2024-05-04
                    'total' => number_format((float) $result->total, 2),
                    'color' => self::getSalesType($result->total)
                );
            }
            return $sales_data;
        }

        // 定义一个函数，根据销售总额返回对应的类型控制颜色用
        public static function getSalesType($total)
        {
            if ($total > 500) {
                return 'green';
            } elseif ($total > 200) {
                return 'red';
            } elseif ($total > 100) {
                return 'blue';
            } elseif ($total > 50) {
                return 'yellow';
            } // 继续添加更多的条件
            else {
                return 'blank';
            }
        }
    }
}
