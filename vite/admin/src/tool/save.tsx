//保存按钮
//将拿到的值推送到服务器端
import { useContext } from "react";
import { Button } from "antd";
import DataContext from "@/tool/dataContext";
import { saceOption } from "@/axios/save";
const App: React.FC = () => {
  //拿到值
  const optionObj = useContext(DataContext);

  //提交动作
  const postData = async () => {
    saceOption(optionObj);
  };
  return (
    <>
      <Button type="primary" onClick={postData}>
        保存
      </Button>
    </>
  );
};

export default App;
