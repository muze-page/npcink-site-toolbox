//保存选项接口
import { Ajaxurl } from "@/tool/dataContext";
import { instance, addParamIfDefined } from "@/axios/public";
import { DataLocal, axiosType } from "@/tool/interface";
//接收选项
export const saceOption = async (data: DataLocal): Promise<boolean> => {
  const params = new URLSearchParams();
  params.append("action", "save_object_option");
  addParamIfDefined(params, "object_data", JSON.stringify(data));
  try {
    const data = (await instance.post(Ajaxurl, params)) as axiosType;
    return data.success;
  } catch (error) {
    console.log(JSON.stringify(data));
    console.log(error);
    return false;
  }
};
