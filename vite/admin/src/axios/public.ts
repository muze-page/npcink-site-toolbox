import axios from "axios";
import { message } from "antd";
import { ApiBase, RestNonce } from "@/tool/dataContext";

export const instance = axios.create({});

export const restInstance = axios.create({
  baseURL: ApiBase,
  headers: {
    "Content-Type": "application/json",
    "X-WP-Nonce": RestNonce,
  },
});

instance.interceptors.response.use(
  (response) => {
    const responseData = response.data;
    if (responseData.success) {
      if (responseData.data?.message) {
        message.success(responseData.data.message);
      }
    } else {
      const errMsg = responseData.data?.error || responseData.data?.message || '未知错误';
      message.error(errMsg);
    }
    return responseData;
  },
  (error) => {
    const errorMessage =
      error.response && error.response.status
        ? `出错： ${error.response.data?.data?.error || error.response.data?.data?.message || error.message}`
        : `出错：${error.message}`;
    message.error(errorMessage);
    console.error(errorMessage);
    return Promise.reject(error);
  }
);

restInstance.interceptors.response.use(
  (response) => {
    const responseData = response.data;
    if (responseData.success) {
      if (responseData.message) {
        message.success(responseData.message);
      }
    } else {
      const errMsg = responseData.message || '未知错误';
      message.error(errMsg);
    }
    return responseData;
  },
  (error) => {
    const errorData = error.response?.data;
    const errMsg = errorData?.message || errorData?.code || error.message;
    message.error(`出错：${errMsg}`);
    console.error(errMsg);
    return Promise.reject(error);
  }
);

export const addParamIfDefined = (
  params: URLSearchParams,
  key: string,
  value: any
) => {
  if (value !== undefined) {
    params.append(key, value);
  }
};
