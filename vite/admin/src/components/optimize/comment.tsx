//评论
import React, { useContext, useState, useEffect } from "react";
import { Switch, Form, InputNumber } from "antd";
import DataContext from "@/tool/dataContext";
import { OptimizeComment } from "@/tool/interface";
import defaultVar from "@/tool/defaultVar";

//选项类型
type FieldType = OptimizeComment;

const App: React.FC = () => {
  //拿到公共值
  const optionObj = useContext(DataContext) || { optimize: {} };

  //简化
  let medium = optionObj.optimize.comment;

  //提供默认值
  if (!medium) {
    medium = defaultVar.optimize.comment;
  }

  //拿到需要的媒体值
  const [FormData, setFormData] = useState(medium);

  //表单同步值
  const onValuesChange = (changedValues: Partial<FieldType>) => {
    setFormData((prevState) => ({ ...prevState, ...changedValues }));
  };

  //打印修改后的值
  //const printData = (value: FieldType) => {
  //  console.log(value);
  //  console.log(optionObj);
  //};

  //修改公共值
  useEffect(() => {
    //这里不能用简化
    optionObj.optimize.comment = FormData;
  }, [FormData]);

  return (
    <Form
      name="medium"
      labelCol={{ span: 8 }}
      wrapperCol={{ span: 16 }}
      style={{ maxWidth: 800 }}
      initialValues={medium}
      autoComplete="off"
      onFinish={() => {}}
      onValuesChange={onValuesChange}
    >
      <Form.Item>
        <h2>评论</h2>
      </Form.Item>

      <Form.Item<FieldType>
        label="两次评论间隔时间"
        name="interval"
        valuePropName="checked"
        extra={"避免短时间内重复灌水评论，对管理员无效"}
      >
        <Switch />
      </Form.Item>
      {FormData.interval && (
        <Form.Item<FieldType>
          label="时间间隔(秒)"
          name="interval_time"
          extra={"指定时间后才能再次评论"}
        >
          <InputNumber min={0} />
        </Form.Item>
      )}
      <Form.Item<FieldType>
        label="限制评论字数"
        name="words_number"
        valuePropName="checked"
        extra={"指定最小和最大评论字数"}
      >
        <Switch />
      </Form.Item>
      {FormData.words_number && (
        <>
          <Form.Item<FieldType> label="最小字数" name="words_number_min">
            <InputNumber min={0} />
          </Form.Item>
          <Form.Item<FieldType> label="最大字数" name="words_number_max">
            <InputNumber min={0} />
          </Form.Item>
        </>
      )}

      <Form.Item<FieldType>
        label="禁止纯英文评论"
        name="english"
        valuePropName="checked"
      >
        <Switch />
      </Form.Item>
      <Form.Item<FieldType>
        label="禁止纯日文评论"
        name="japanese"
        valuePropName="checked"
      >
        <Switch />
      </Form.Item>
      <Form.Item<FieldType>
        label="仅限评论一次"
        name="only"
        valuePropName="checked"
        extra={"管理员不受此影响"}
      >
        <Switch />
      </Form.Item>
    </Form>
  );
};

export default App;
