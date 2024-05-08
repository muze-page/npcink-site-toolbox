import React from "react";
import type { CalendarProps } from "antd";
import { Calendar } from "antd";
import type { Dayjs } from "dayjs";
import { day_data } from "./tool/dataContext";



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

const dateCellRender = (value: Dayjs) => {
  const listData = getListData(value);

  const styles = { "--bgColor": listData?.color } as React.CSSProperties;
  return (
    <div className="calendar-box" style={styles}>
      <span> {listData?.total}</span>
    </div>
  );
};

//年度
const getMonthData = (value: Dayjs) => {
  if (value.month() === 8) {
    return 1394;
  }
};

const App: React.FC = () => {
  const monthCellRender = (value: Dayjs) => {
    const num = getMonthData(value);
    return num ? (
      <div className="notes-month">
        <section>{num}</section>
        <span>Backlog number</span>
      </div>
    ) : null;
  };

  const cellRender: CalendarProps<Dayjs>["cellRender"] = (current, info) => {
    if (info.type === "date") return dateCellRender(current);
    if (info.type === "month") return monthCellRender(current);
    return info.originNode;
  };

  return <Calendar cellRender={cellRender} />;
};

export default App;
