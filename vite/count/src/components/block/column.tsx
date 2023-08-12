import { useRef, useEffect } from "react";
import * as echarts from "echarts/core";
import { GridComponent, TitleComponent,TooltipComponent } from "echarts/components";
import { BarChart } from "echarts/charts";
import { CanvasRenderer } from "echarts/renderers";

echarts.use([GridComponent, BarChart, CanvasRenderer,TitleComponent,TooltipComponent]);

const option = {
  title: {
    text: "最近7天总销售订单（已减退款订单）",
  },
  tooltip: {
    valueFormatter: (value: number) => value.toFixed(0) + "个",
  },
  xAxis: {
    type: "category",
    data: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
  },
  yAxis: {
    type: "value",
  },
  series: [
    {
      name: "总销售订单",
      data: [120, 200, 150, 80, 70, 110, 130],
      type: "bar",
      showBackground: true,
      backgroundStyle: {
        color: "rgba(180, 180, 180, 0.2)",
      },
      label: {
        show: true,
        position: "insideTop", //在上方显示
        textStyle: {
          //数值样式
          color: "#fff",
          fontSize: 12,
          fontWeight: "bold",
        },
      },
    },
  ],
};

const App = () => {
  const chartRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    //找节点
    const myChart = echarts.init(chartRef.current);

    //做数据
    myChart.setOption(option);

    // 清除图表实例
    return () => {
      myChart.dispose();
    };
  }, []);

  return <div ref={chartRef} style={{ width: "600px", height: "300px" }}></div>;
};

export default App;
