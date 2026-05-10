//准备初始数据
import data from "@/store/defaultVar";
import { PublicShareData } from "@/store/interface";
import DefaultVar from "@/store/defaultVar";
//开发环境状态
const state: boolean = import.meta.env.VITE_STATE;

//输出选项值
const getDataLocal = () => {
  if (state) {
    //开发
    return data;
  } else {
    //打包
    const wl = window.dataLocal;
    return wl !== "" && typeof wl === "object" ? wl : DefaultVar;
  }
};

//对硬件值进行处理后传出
export const publicShareData: PublicShareData = getDataLocal();
//console.log(publicShareData);
