import { useState } from "react";
import { Button, Switch, Form, Input, InputNumber } from "antd";

type DataLocal = {
  option: FieldType;
};

type FieldType = {
  name?: string;
  age?: number;
  handle?: boolean;
};

const state: boolean = import.meta.env.VITE_STATE;

const option = {
  option: {
    name: import.meta.env.VITE_OPTION_NAME,
    age: parseInt(import.meta.env.VITE_OPTION_AGE),
    handle: import.meta.env.VITE_OPTION_HANDLE === "true",
  },
};

function getDataLocal(): DataLocal {
  if (state) {
    return option;
  } else {
    return (window as any).dataLocal;
  }
}

const dataLocal: DataLocal = getDataLocal();
const getOption = dataLocal?.option;

const App = () => {
  const [formData, setFormData] = useState<FieldType>({
    name: getOption?.name,
    age: getOption?.age,
    handle: getOption?.handle,
  });

  const onFormSubmit = (values: FieldType) => {
    console.log(values);
    // 处理表单提交逻辑
  };

  return (
    <>
      <h1>{formData.name}</h1>
      <p>Age: {formData.age}</p>
      <p>状态: {formData.handle?.toString()}</p>

      <Form
        name="basic"
        labelCol={{ span: 8 }}
        wrapperCol={{ span: 16 }}
        style={{ maxWidth: 600 }}
        initialValues={getOption}
        autoComplete="off"
        onFinish={onFormSubmit}
      >
        <Form.Item<FieldType> label="用户名" name="name">
          <Input
           
            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
          />
        </Form.Item>

        <Form.Item<FieldType> label="年龄" name="age">
          <InputNumber
            min={1}
            max={100}
            onChange={(value) => setFormData({ ...formData, age: value })}
          />
        </Form.Item>

        <Form.Item<FieldType> label="是否展示" name="handle">
          <Switch
            checked={formData.handle}
            onChange={(value) => setFormData({ ...formData, handle: value })}
          />
        </Form.Item>

        <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
          <Button type="primary" htmlType="submit">
            提交
          </Button>
        </Form.Item>
      </Form>
    </>
  );
};

export default App;
