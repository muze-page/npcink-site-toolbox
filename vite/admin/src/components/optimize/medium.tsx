import React, { useContext, useState, useEffect } from "react";
import { Switch, Form } from "antd";
import DataContext from "@/tool/dataContext";
import { OptimizeSite } from "@/tool/interface";

type FieldType = OptimizeSite;

const App: React.FC = () => {
  const optionObj = useContext(DataContext) || { optimize: {} };
  const [FormData, setFormData] = useState(optionObj.optimize.site);

  const onValuesChange = (changedValues: Partial<FieldType>) => {
    setFormData((prevState) => ({ ...prevState, ...changedValues }));
  };

  useEffect(() => {
    optionObj.optimize.site = FormData;
  }, [FormData]);

  return (
    <Form
      name="opts"
      labelCol={{ span: 12 }}
      wrapperCol={{ span: 8 }}
      style={{ maxWidth: 600 }}
      initialValues={optionObj.optimize.site}
      autoComplete="off"
      onFinish={() => {}}
      onValuesChange={onValuesChange}
    >
      <Form.Item>
        <h2>站点</h2>
      </Form.Item>

      <Form.Item<FieldType>
        label="禁止网站title中的 “-” 被转义"
        name="no_escape"
        valuePropName="checked"
      >
        <Switch />
      </Form.Item>
      <Form.Item<FieldType>
        label="文章关键词自动添加内链链接代码"
        name="add_inks"
        valuePropName="checked"
        extra={
          <a href="https://www.npc.ink/15286.html?=magick-mami" target="_blank">
            详细介绍
          </a>
        }
      >
        <Switch />
      </Form.Item>
    </Form>
  );
};

export default App;
