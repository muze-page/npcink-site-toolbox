/**
 * 短代码 功能
 */
import { useState, useContext, useEffect } from "react";
import { Form, Switch } from "antd";
import { DataContext } from "@/tool/dataContext";
import { CodeCompose } from "@/tool/interface";
import { defaultVarOption } from "@/tool/defaultVar";
import { AntConfig } from "@/tool/tool";
import Preview from "@/basic/preview";
import Runcode from "@/assets/shortcode/compose/运行代码.png";
import SingleList from "@/assets/shortcode/compose/文章列表.png";
import CopyBtn from "@/assets/shortcode/compose/复制按钮.png";
type FieldType = CodeCompose;

//Ant 组件配置
const fromConfig = AntConfig.from;

const App: React.FC = () => {
  //拿到默认选项值和修改方法
  const { optionData, updateOption } = useContext(DataContext);
  const publicData =
    optionData.shortcode?.compose || defaultVarOption.shortcode.compose;

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

  //表单值发生变化时更新选项值
  useEffect(() => {
    updateOption("shortcode", "compose", formData);
  }, [formData]);

  return (
    <>
      <Form
        name="compose"
        labelCol={{ span: fromConfig.labelCol }}
        wrapperCol={{ span: fromConfig.wrapperCol }}
        style={{ maxWidth: fromConfig.maxWidth }}
        initialValues={publicData}
        autoComplete="off"
        onFinish={() => {}}
        onValuesChange={onValuesChange}
      >
        <Form.Item>
          <h2>板式</h2>
        </Form.Item>

        <Form.Item<FieldType>
          label="文章列表"
          name="single_list"
          valuePropName="checked"
          extra={
            <>
              "填写若干文章 ID 就能生成漂亮的文章列表"，
              <Preview title="文章列表" img={SingleList} />
            </>
          }
        >
          <Switch />
        </Form.Item>
        <Form.Item<FieldType>
          label="复制"
          name="single_copy"
          valuePropName="checked"
          extra={
            <>
              "第一个属性是按钮名称，第二个属性是弹窗内容，第三个属性是跳转网址"，
              <Preview title="复制按钮" img={CopyBtn} />
            </>
          }
        >
          <Switch />
        </Form.Item>
        <Form.Item<FieldType>
          label="前端运行代码"
          name="runcode"
          extra={
            <>
              1、仅支持经典编辑器，2、[runcode]和[/runcode]不能换行，会有换行符,
              <pre className="pre-meat">&lt;runcode&gt;&lt;/runcode&gt;</pre>，
              <Preview title="在线运行前端代码" img={Runcode} />
            </>
          }
        >
          <Switch />
        </Form.Item>
      </Form>
    </>
  );
};

export default App;
