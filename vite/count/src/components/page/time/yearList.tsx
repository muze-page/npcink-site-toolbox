import React from "react";
import { Calendar, CalendarProps, theme } from "antd";
import type { Dayjs } from "dayjs";
import { day_data } from "../../tool/dataContext";

//月度
const getListData = (value: Dayjs) => {
  //拿到当前时间
  const time = value.format("YYYY-MM-DD");

  for (let i = 0; i < day_data.length; i++) {
    if (day_data[i].time === time) {
      return day_data[i];
    }
  }

  return null;
};

//对比时间
const compareDates = (date1: string, date2: string) => {
  // 将字符串形式的时间转换为日期对象
  const d1 = new Date(date1);
  const d2 = new Date(date2);

  // 使用时间戳进行比较
  if (d1.getTime() === d2.getTime()) {
    return 0; // 时间相等
  } else if (d1.getTime() < d2.getTime()) {
    return -1; // 第一个时间小于第二个时间
  } else {
    return 1; // 第一个时间大于第二个时间
  }
};

//拿到今天的时间
const getCurrentDate = () => {
  const today = new Date();

  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, "0"); // 月份从 0 开始，需要加 1
  const day = String(today.getDate()).padStart(2, "0");

  return `${year}-${month}-${day}`;
};

const dateCellRender = (value: Dayjs) => {
  const listData = getListData(value);

  const styles = { "--bgColor": listData?.color } as React.CSSProperties;

  //当前时间大于表格时间为true
  const today = getCurrentDate();
  const tableTime = value.format("YYYY-MM-DD");
  const switchTime = compareDates(today, tableTime) === 1;
  return listData ? (
    <div className="calendar-box" style={styles}>
      <span>{switchTime ? listData.total ?? "0" : ""}</span>
    </div>
  ) : null;
};

//年度
//准备年度数据
//显式声明 monthlySales 对象的类型，告诉 TypeScript 它的键是字符串类型的月份
interface MonthlySales {
  [month: string]: number;
}
const calculateMonthlySales = (
  data: { time: string; total: string }[]
): { month: string; total: string }[] => {
  const monthlySales: MonthlySales = {};

  // 遍历 day_data 数组
  data.forEach((item: { time: string; total: string }) => {
    const month = item.time.substring(0, 7); // 截取年月部分作为键值
    const total = parseFloat(item.total); // 将 total 转换为浮点数

    // 如果该月份已经存在，则累加销售额，否则初始化为当前销售额
    if (monthlySales[month]) {
      monthlySales[month] += total;
    } else {
      monthlySales[month] = total;
    }
  });

  // 将结果转换为对象数组形式
  const monthlySalesArray = Object.keys(monthlySales).map((month) => ({
    month: month,
    total: monthlySales[month].toFixed(0), // 保留两位小数
  }));

  return monthlySalesArray;
};

const getMonthData = (value: Dayjs) => {
  //拿到当前月份
  const month = value.format("YYYY-MM");
  //获得月份列表
  const monthlySales = calculateMonthlySales(day_data);

  //开始循环输出对象
  for (let i = 0; i < monthlySales.length; i++) {
    if (monthlySales[i].month === month) {
      return monthlySales[i];
    }
  }

  return null;
};

//做样式
const monthCellRender = (value: Dayjs) => {
  const num = getMonthData(value);
  return num ? <div className="month-box">{num.total}</div> : null;
};

const App: React.FC = () => {
  const cellRender: CalendarProps<Dayjs>["cellRender"] = (
    current: Dayjs,
    info: { type: string; originNode: any }
  ) => {
    if (info.type === "date") return dateCellRender(current);
    if (info.type === "month") return monthCellRender(current);
    return info.originNode;
  };

  //卡片
  const { token } = theme.useToken();
  const wrapperStyle: React.CSSProperties = {
    width: 900,
    border: `1px solid ${token.colorBorderSecondary}`,
    borderRadius: token.borderRadiusLG,
  };

  return (
    <>
      <h2>年度销售额</h2>
      <div style={wrapperStyle}>
        <Calendar
          cellRender={cellRender}
          fullscreen={false}
          style={{ overflow: "hidden" }}
        />
      </div>
    </>
  );
};

export default App;
