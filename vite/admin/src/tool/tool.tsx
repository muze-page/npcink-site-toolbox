//ant 组件公共配置信息
interface AntFrom {
  from: {
    labelCol: number;
    wrapperCol: number;
    maxWidth: number;
  };
}

export const AntConfig: AntFrom = {
  //表单配置
  from: {
    labelCol: 8,
    wrapperCol: 16,
    maxWidth: 900,
  },
};

//网址验证
export const validateLink = (_: any, value: string) => {
  const urlPattern =
    /^(https?):\/\/(?:www\.)?([a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*)(?:\/[^\s]*)?$/;
  if (!value || value.match(urlPattern)) {
    return Promise.resolve();
  }
  return Promise.reject("请输入有效的链接 URL");
};
