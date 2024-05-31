/**
 * 页面优化 - 权限
 */
import { useState, useContext, useEffect } from "react";
import { Form, Select } from "antd";
import DataContext from "@/tool/dataContext";
import { PageJurisdiction } from "@/tool/interface";
import defaultVar from "@/tool/defaultVar";
import { AntConfig } from "@/tool/tool";
import { getCategoryData } from "@/axios/axios";

type FieldType = PageJurisdiction;

//Ant 组件配置
const fromConfig = AntConfig.from;

const App: React.FC = () => {
  //准备默认值
  const optionObj = useContext(DataContext) ?? { page: {} };
  const publicData =
    optionObj.page?.jurisdiction || defaultVar.page.jurisdiction;

  //存储表单值
  const [formData, setFormData] = useState(publicData || {});

  //修改表单值
  const onValuesChange = (
    changedValues: Partial<FieldType>,
    _allValues: FieldType
  ) => {
    setFormData((prevState) => ({
      ...prevState,
      ...changedValues,
    }));
  };

  //修改公共值
  useEffect(() => {
    optionObj.page = {
      ...optionObj.page,
      jurisdiction: formData,
    };
  }, [formData]);

  //调试
  const print = () => {
    console.log(formData);
  };

  //准备分类数据
  const options = [
    { label: "文章一", value: 19 },
    { label: "文章二", value: 24 },
    { label: "文章三", value: 27 },
  ];

  //打印表单
  const handleChange = (value: string[]) => {
    console.log(`selected ${value}`);
  };

  //获取分类数组
  const getData = async () => {
    try {
      // 获取原始数据
      const list = await getCategoryData();
      console.log(list);
    } catch (error) {
      console.error("Error fetching table data:", error);
    }
  };

  return (
    <>
      <Form
        name="jurisdiction"
        labelCol={{ span: fromConfig.labelCol }}
        wrapperCol={{ span: fromConfig.wrapperCol }}
        style={{ maxWidth: fromConfig.maxWidth }}
        initialValues={publicData}
        autoComplete="off"
        onFinish={() => {}}
        onValuesChange={onValuesChange}
      >
        <Form.Item>
          <h2>未登录权限</h2>
          <button onClick={print}>打印</button>
          <button onClick={getData}>获取对象</button>
        </Form.Item>

        <Form.Item<FieldType>
          label="隐藏指定分类"
          name="category_id"
          extra={"输入文章ID"}
        >
          <Select
            mode="multiple"
            allowClear
            style={{ width: "100%" }}
            placeholder="请选择要隐藏的文章"
            defaultValue={["19", "27"]}
            onChange={handleChange}
            options={options}
          />
        </Form.Item>
      </Form>
    </>
  );
};

export default App;
