//准备初始数据
import { createContext } from "react";

//准备类型
type DataLocal = {
  option: FieldType;
};

type FieldType = {
  name?: string;
  age?: number;
  handle?: boolean;
};


//开发环境状态
const state: boolean = import.meta.env.VITE_STATE;

//组建开发环境下的对象
const option = {
  option: {
    name: import.meta.env.VITE_OPTION_NAME,
    age: parseInt(import.meta.env.VITE_OPTION_AGE),
    handle: import.meta.env.VITE_OPTION_HANDLE === "true",
  },
};

//输出选项值
function getDataLocal(): DataLocal {
  if (state) {
    //开发
    return option;
  } else {
    //打包
    return (window as any).dataLocal.option;
  }
}

//传值
const dataObject: DataLocal = getDataLocal();

const DataContext = createContext(dataObject);

export default DataContext;
