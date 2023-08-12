export type DataLocal = {
  //小模块
  count: Count;
  //单柱状图
  column: Column;
  //多柱状图
  ColumnMore: ColumnMore;
};

//模块
export type Count = {
  title: string; //标题
  num: number; //数量
  unit: string; //单位
  icon: string; //图标
};

//单柱状图
export type Column = {
  title: string; //标题
  x: Array<string>; //横轴数据
  s: {
    title: string; //提示标题
    data: Array<number>; //数据
  };
};

//多柱状图
export type ColumnMore = {
  title: string; //标题
  dataset: Array<Array<string | number>>; //数据
};
