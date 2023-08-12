//发文统计
import ColumnMore from "./block/column_more";
import Count from "./block/count";
const data = {
  title: "统计", //标题
  dataset: [
    ["time", "2015", "2016", "2017"],
    ["Matcha Latte", 43.3, 85.8, 93.7],
    ["Milk Tea", 83.1, 73.4, 55.1],
    ["Cheese Cocoa", 86.4, 65.2, 82.5],
    ["Walnut Brownie", 72.4, 53.9, 39.1],
  ],
};
//表格用数据
const datas = [
  {
    title: "今日发文",
    num: 10,
    unit: "篇",
    icon: "dashicons dashicons-database-remove",
  },
  {
    title: "今日评论",
    num: 10,
    unit: "篇",
    icon: "dashicons dashicons-database-remove",
  },
  {
    title: "今日注册",
    num: 10,
    unit: "个",
    icon: "dashicons dashicons-database-remove",
  },
];
const App = () => {
  return (
    <>
      <div className="single-box">
        <div className="left">
          <ColumnMore data={data} />
        </div>
        <div className="right">
          {datas.map((item, index) => (
            <Count key={index} data={item} />
          ))}
        </div>
      </div>
    </>
  );
};
export default App;
