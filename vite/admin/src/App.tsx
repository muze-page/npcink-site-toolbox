import { useState } from "react";
import { Button, Switch, Form, Input, InputNumber } from "antd";

//获取数据
//提交数据

//传来值的类型
type DataLocal = {
  option: FieldType;
};

//选项类型
type FieldType = {
  name?: string;
  age?: number;
  handle?: boolean;
};

//获取开发环境状态
const state = import.meta.env.VITE_STATE;

const option = {
  option: {
    name: import.meta.env.VITE_OPTION_NAME,
    age: parseInt(import.meta.env.VITE_OPTION_AGE),
    handle: import.meta.env.VITE_OPTION_HANDLE === "true", // 将字符串转为布尔值
  },
};

//获取传来的值
function getDataLocal() {
  if (state) {
    // 如果是开发环境，返回从.env.development文件中获取的数据
    return option;
  } else {
    // 如果是生产环境，返回从wp_localize_script传递的数据
    return (window as any).dataLocal;
  }
}

// 在组件中使用getDataLocal()函数来获取dataLocal数据
const dataLocal: DataLocal = getDataLocal();

//获取需要的选项
const getOption = dataLocal?.option;

const App = () => {
  const [formData, setFormData] = useState({
    name: getOption?.name,
    age: getOption?.age,
    handle: getOption?.handle,
  });

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
        initialValues={{ remember: true }}
        autoComplete="off"
      >
        <Form.Item<FieldType> label="用户名" name="username">
          <Input
            defaultValue={formData.name}
            onChange={(e) => setFormData({ ...formData, name: e.target.value })}
          />
        </Form.Item>

        <Form.Item<FieldType> label="年龄" name="password">
          <InputNumber
            min={1}
            max={100}
            defaultValue={formData.age}
            onChange={(value) => setFormData({ ...formData, age: value })}
          />
        </Form.Item>

        <Form.Item<FieldType> label="是否展示" name="remember">
          <Switch
            checked={formData.handle}
            onChange={(value) => setFormData({ ...formData, handle: value })}
          />
        </Form.Item>

        <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
          <Button
            type="primary"
            htmlType="submit"
            onClick={() => console.log(formData)}
          >
            提交
          </Button>
        </Form.Item>
      </Form>
    </>
  );
};

export default App;
