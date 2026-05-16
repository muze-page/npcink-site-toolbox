import { restInstance } from "@/axios/public";
import { Option } from "@/tool/interface";
import { defaultVarOption } from "@/tool/defaultVar";

export const saceOption = async (data: Option) => {
  const payload = data || defaultVarOption;

  try {
    await restInstance.post("/settings", payload);
  } catch (error) {
    console.log(`保存设置选项时出错：${error}`);
    throw error;
  }
};
