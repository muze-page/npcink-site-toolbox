import React from "react";
import Site from "./site/index";
import { Switch } from "antd";

const onChange = (checked: boolean) => {
  console.log(`switch to ${checked}`);
};

const App: React.FC = () => <>
{/**站点优化 */}
  <Site />
  <Switch defaultChecked onChange={onChange} />
</>;

export default App;
