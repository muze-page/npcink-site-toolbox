import React from "react";
import { Switch } from "antd";

const onChange = (checked: boolean) => {
  console.log(`switch to ${checked}`);
};

const App: React.FC = () => (
  <>
    <h3>站点</h3>
    测试下，控制网站顶部展示文本
    <Switch defaultChecked onChange={onChange} />
  </>
);

export default App;
