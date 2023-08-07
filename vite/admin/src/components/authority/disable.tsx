//权限 - 禁用
import React from "react";
import { useState, useContext, useEffect } from "react";
import { Switch, Form } from "antd";
import DataContext from "@/tool/dataContext";
import { AuthorityDisable } from "@/tool/interface";
import defaultVar from "@/tool/defaultVar";

//选项类型
type FieldType = AuthorityDisable;

const App: React.FC = () => {
  //拿到值
  const optionObj = useContext(DataContext) ?? { optimize: {} };

  //简化
  let publicData = optionObj.authority.disable;

  //提供默认值
  if (!publicData) {
    publicData = defaultVar.authority.disable;
  }

  //创建变量并设默认值
  const [FormData, setFormData] = useState(publicData || {});

  //表单同步修改值
  const onValuesChange = (
    changedValues: Partial<FieldType>,
    _allValues: FieldType
  ) => {
    setFormData((prevState) => ({
      ...prevState,
      ...changedValues,
    }));
  };

  //打印修改后的值
  //const printData = (value: FieldType) => {
  //  console.log(value);
  //};

  // 表单值发生变化时更新dataContext的值
  useEffect(() => {
    optionObj.authority.disable = FormData;
  }, [FormData]);

  return (
    <>
      <Form
        name="disable"
        labelCol={{ span: 8 }}
        wrapperCol={{ span: 16 }}
        style={{ maxWidth: 800 }}
        //表单默认值，只有初始化以及重置时生效
        initialValues={publicData}
        //自动填充功能禁用
        autoComplete="off"
        //指定当表单提交时要执行的回调函数
        onFinish={() => {}}
        //指定当表单字段值发生变化时要执行的回调函数
        onValuesChange={onValuesChange}
      >
        <Form.Item>
          <h2>站点</h2>
        </Form.Item>

        <Form.Item<FieldType>
          label="禁用更新"
          name="renew"
          valuePropName="checked"
          extra={"WordPress、主题和插件不再提示更新"}
        >
          <Switch />
        </Form.Item>
      </Form>
    </>
  );
};

export default App;
