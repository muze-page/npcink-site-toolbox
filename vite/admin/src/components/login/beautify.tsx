/**
 *
 * 介绍：美化
 */
//站点 - 模版
import { useState, useContext, useEffect } from "react";
import { Switch, Form, ColorPicker, Input, InputNumber } from "antd";
import { FileImageOutlined } from "@ant-design/icons";
import DataContext from "@/tool/dataContext";
import { LoginBeautify } from "@/tool/interface";
import defaultVar from "@/tool/defaultVar";

import type { Color } from "antd/es/color-picker";
import { AntConfig } from "@/tool/tool";

type FieldType = LoginBeautify;

//Ant 组件配置
const fromConfig = AntConfig.from;

//处理颜色格式
const getHexString = (color: Color | string): string => {
  return typeof color === "string" ? color : color.toHexString();
};

const App: React.FC = () => {
  //准备默认值
  const optionObj = useContext(DataContext) ?? { login: {} };
  const publicData = optionObj.login?.beautify || defaultVar.login.beautify;

  //存储表单值
  const [formData, setFormData] = useState(publicData || {});

  //修改表单值
  const onValuesChange = (
    changedValues: Partial<FieldType>,
    allValues: FieldType
  ) => {
    const updatedValues = {
      ...changedValues,
      background_left: getHexString(allValues.background_left || ""),
      background_right: getHexString(allValues.background_right || ""),
    };

    setFormData((prevState) => ({
      ...prevState,
      ...updatedValues,
    }));
  };

  //修改公共值
  useEffect(() => {
    optionObj.login = {
      ...optionObj.login,
      beautify: formData,
    };
  }, [formData]);



  return (
    <>
      <Form
        name="login_beautify"
        labelCol={{ span: fromConfig.labelCol }}
        wrapperCol={{ span: fromConfig.wrapperCol }}
        style={{ maxWidth: fromConfig.maxWidth }}
        initialValues={publicData}
        autoComplete="off"
        onFinish={() => {}}
        onValuesChange={onValuesChange}
      >
        <Form.Item>
          <h2>美化</h2>
        </Form.Item>
        <Form.Item<FieldType>
          label="LOGO链接"
          name="modify_login_link"
          valuePropName="checked"
          extra={"改为首页链接"}
        >
          <Switch />
        </Form.Item>
        <Form.Item<FieldType>
          label="移除语言选择框"
          name="remove_langue"
          valuePropName="checked"
          extra={"移除登录页面语言选择框"}
        >
          <Switch />
        </Form.Item>

        <Form.Item<FieldType>
          label="自定义登录页"
          name="custom_login_page"
          valuePropName="checked"
          extra={""}
        >
          <Switch />
        </Form.Item>

        {formData.custom_login_page && (
          <>
            <Form.Item<FieldType>
              label="左下角颜色"
              name="background_left"
              extra={""}
            >
              <ColorPicker showText />
            </Form.Item>
            <Form.Item<FieldType>
              label="右上角颜色"
              name="background_right"
              extra={""}
            >
              <ColorPicker showText />
            </Form.Item>

            <Form.Item<FieldType>
              label="LOGO尺寸(px)"
              name="logo_size"
              extra={"默认84，最大180（推荐宽高比为1:1的正方形LOGO）"}
            >
              <InputNumber
                min={0}
                max={180}
                formatter={(value) => `${value}px`}
              />
            </Form.Item>

            <Form.Item<FieldType> label="顶部LOGO" name="top_logo" extra={""}>
              <Input
                addonBefore={<FileImageOutlined />}
                placeholder="图片网址"
              />
            </Form.Item>

            <Form.Item<FieldType>
              label="文字背景图"
              name="background_img"
              extra={""}
            >
              <Input
                addonBefore={<FileImageOutlined />}
                placeholder="图片网址"
              />
            </Form.Item>
          </>
        )}
      </Form>
    </>
  );
};

export default App;
