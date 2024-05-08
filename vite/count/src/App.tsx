import "./App.css";
//打包前注释
//import "./load-styles.css";
import B2Shop from "@/components/page/b2Shop/index";
import SingleCount from "@/components/page/singleCount/index";
import Demo from "@/components/demo";
import { ConfigProvider } from "antd";
import zhCN from "antd/locale/zh_CN";
function App() {
  return (
    <ConfigProvider locale={zhCN}>
      <Demo />
      {/**
       * 销售统计
       */}

      <B2Shop />
      {/**
       * 周数据预览
       */}

      <SingleCount />
    </ConfigProvider>
  );
}

export default App;
