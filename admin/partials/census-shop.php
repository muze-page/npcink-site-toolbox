<?php
/**
 * 商城统计
 */

if (!class_exists('Magick_Mixtrue_Census_Shop')) {
    class Magick_Mixtrue_Census_Shop
    {

        public function __construct()
        {

            self::init_actions();

        }

        public static function init_actions()
        {

            //add_action('admin_init', array(__CLASS__, 'magick_plugin_options'));

        }
        public static function load_content()
        {
            ?>
             <!-- 在默认WordPress“包装”容器中创建标题 -->
	        <div class="wrap magick-content">

            <!--标题-->
		     <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
             <hr />
             <?php echo self::get_sql_time() ?>
             <section class="magick_shop_box">
        <div class="content">
            <div class="child-box">
                <span>待发货</span>
                <div class="child">
                    <p><span>1</span>个</p>
                    <span class="dashicons dashicons-store"></span>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="child-box">
                <span>今日总销售额（已减退款）</span>
                <div class="child">
                    <p><span>2</span>￥</p>
                    <span class="dashicons dashicons-insert"></span>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="child-box">
                <span>今日总订单（已减退款）</span>
                <div class="child">
                    <p><span>3</span>个</p>
                    <span class="dashicons dashicons-database-add"></span>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="child-box">
                <span>今日总退款</span>
                <div class="child">
                    <p><span>4</span>￥</p>
                    <span class="dashicons dashicons-remove"></span>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="child-box">
                <span>今日总退款订单</span>
                <div class="child">
                    <p><span>5</span>个</p>
                    <span class="dashicons dashicons-database-remove"></span>
                </div>
            </div>
        </div>

    </section>

    <!--四栏分隔-->
    <style>
        .magick_four-column .content > div {
  width: 600px;
  height: 300px;
}
        </style>
    <section class="magick_four-column">
        <div class="content">
            <!--最近7天总销售额-->
            <div id="total-sales"></div>
        </div>
        <div class="content">
            <!--最近7天总销售订单-->
            <div id="total-order"></div>
        </div>
        <div class="content">
            <!--最近7天总退款销售额-->
            <div id="total-refund"></div>
        </div>
        <div class="content">
            <!--最近7天总退款订单-->
            <div id="total-refund-order"></div>
        </div>
    </section>
            </div><!-- end wrap-->
            <?php
}

        /**
         * 获取待发货订单
         */
        public function get_shop_watit_deliver()
        {
            $table_name = $this->wpdb->prefix . 'zrz_order';
            $num = $this->wpdb->get_var("SELECT COUNT(*) FROM $table_name where order_state='f'");
            return $num;
        } //end magick_get_shop_watit_deliver()

        /**
         * 拿到指定时间内的所有数据
         */
        public static function get_sql_time()
        {
            //用WordPress提供的全局变量
            global $wpdb;
            //实例化工具
            $tool = new Magick_Mixtrue_Tool;
            $time = $tool->get_time();
            $time = $time['a'];
            $table_name = $wpdb->prefix . 'zrz_order';
            //创建数组，存储数据
            $array = array();

            //获取近7天的销售数据
            $order_data_seven = "SELECT order_type,order_commodity,order_state,order_date,order_total FROM $table_name WHERE  order_date > '$time[6]'";
            $order_data = $wpdb->get_results($order_data_seven, ARRAY_A);

            //整理 - 将拿到的数据以时间为键名保存数据
            $array_data_time = array();
            for ($i = 0; $i < count((array) $time); $i++) {
                //当前时间
                $t = $time[$i];
                //将时间处理下，方便比较
                $handle_time = date("Y-m-d", strtotime($t));

                //找到符合当前时间的值
                foreach ($order_data as $v) {
                    //拿到当前键值的时间
                    $value_time = $v['order_date'];
                    //格式下一下，方便比较
                    $handle_value = date("Y-m-d", strtotime($value_time));
                    //商城订单
                    if ($handle_value == $handle_time) {
                        $array_data_time[$handle_time][] = $v;
                    }

                }

                $tool->p($array_data_time);
                return $array_data_time;

            }
            /*打印下，看看里面有啥*/
            //$tool->p($order_data);

            //转成数组，每天的总销售额、总订单、总退款额、总退款订单

            /**
             * 今天需要的数据
             */

            //获取待发货订单

            //获取最近7天销售数组
            $order_seven_total = array();
            //拿到筛选后的数组
            $order_seven_total = array_filter($order_data, function ($v) {
                $switch = false;
                //商城订单
                if ($v['order_type'] == 'gx') {
                    //实物
                    if ($v['order_commodity'] == '1') {
                        //已发货
                        if ($v['order_state'] == 'q') {$switch = true;}
                        //已签收
                        if ($v['order_state'] == 'c') {$switch = true;}
                    }
                }
                return $switch;
            });

            $tool->p($order_seven_total);

            //获取最近7天退款数组
            $total_refund_seven_data = array();
            //拿到筛选后的数组
            $total_refund_seven_data = array_filter($order_data, function ($v) {
                $switch = false;
                //商城订单
                if ($v['order_type'] == 'gx') {
                    //实物
                    if ($v['order_commodity'] == '1') {
                        //已退款
                        if ($v['order_state'] == 't') {$switch = true;}

                    }
                }
                return $switch;
            });

            //每天的总销售额（去除退款）
            $ever_day_sale_total = array();
            for ($i = 0; $i < count((array) $time); $i++) {
                //当前时间
                $today = $time[$i];
                //找到数组中，符合当前条件的值
                $order_seven_total;

            }

            //7天每天的总销售额（减去退款）
            $total_sales_volume = 0;
            foreach ($order_seven_total as $value) {
                $total_sales_volume = $value['order_total'];
            }
            //7天中每天的总销售订单（减去退款）
            $total_order = count($order_seven_total);

            //7天总退款额
            $total_refund = 0;
            foreach ($total_refund_seven_data as $value) {
                $total_refund = $value['order_total'];
            }
            //7天总退款订单
            $total_refund_order = count($total_refund_seven_data);

            //总销售额
            $array['sales']['total'] = $total_sales_volume;
            //总销售订单
            $array['sales']['order'] = $total_order;

            //总退款额
            $array['refund']['total'] = $total_refund;
            //总退款订单
            $array['refund']['order'] = $total_refund_order;

            //return $array;
        }

    } //end class
}
