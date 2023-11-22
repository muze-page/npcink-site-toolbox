//功能 - 下载数据库文件
import React from "react";
import { Form, Select, Button } from "antd";
import { DownloadOutlined } from "@ant-design/icons";
import { AntConfig } from "@/tool/tool";
import { get_all_table_name } from "@/tool/axios";

//Ant 组件配置
const fromConfig = AntConfig.from;

//准备模拟下拉数据
const optionData = [
  {
    value: "jack",
    label: "Jack",
  },
  {
    value: "lucy",
    label: "Lucy",
  },
  {
    value: "tom",
    label: "Tom",
  },
];

const App: React.FC = () => {
  const onChange = (value: string) => {
    console.log(`selected ${value}`);
  };

  const onSearch = (value: string) => {
    console.log("search:", value);
  };

  // Filter `option.label` match the user type `input`
  const filterOption = (
    input: string,
    option?: { label: string; value: string }
  ) => (option?.label ?? "").toLowerCase().includes(input.toLowerCase());

  return (
    <>
      <Form
        name="down_database"
        labelCol={{ span: fromConfig.labelCol }}
        wrapperCol={{ span: fromConfig.wrapperCol }}
        style={{ maxWidth: fromConfig.maxWidth }}
        //自动填充功能禁用
        autoComplete="off"
        //指定当表单提交时要执行的回调函数
        onFinish={() => {}}
      >
        <Form.Item>
          <h2>下载指定数据库表内容</h2>
        </Form.Item>

        <Form.Item label="选择数据库" extra={"选中您需要下载的数据库"}>
          <Select
            showSearch
            optionFilterProp="children"
            style={{ width: 200 }}
            onChange={onChange}
            onSearch={onSearch}
            filterOption={filterOption}
            options={optionData}
          />
        </Form.Item>
        <Form.Item label="点击">
          <Button
            type="primary"
            icon={<DownloadOutlined />}
            onClick={get_all_table_name}
          >
            下载
          </Button>
        </Form.Item>
      </Form>
    </>
  );
};

export default App;
