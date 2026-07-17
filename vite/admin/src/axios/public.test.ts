import { AxiosError, type AxiosAdapter, type AxiosResponse } from "axios";
import { afterEach, beforeEach, describe, expect, it, vi } from "vitest";

import { restInstance } from "@/axios/public";

const noticeMocks = vi.hoisted(() => ({
  success: vi.fn(),
  error: vi.fn(),
}));

vi.mock("@/tool/notice", () => ({
  notice: noticeMocks,
}));

function responseAdapter(data: unknown): AxiosAdapter {
  return async (config): Promise<AxiosResponse> => ({
    data,
    status: 200,
    statusText: "OK",
    headers: {},
    config,
  });
}

function errorAdapter(data: unknown): AxiosAdapter {
  return async (config): Promise<AxiosResponse> => {
    const response: AxiosResponse = {
      data,
      status: 500,
      statusText: "Internal Server Error",
      headers: {},
      config,
    };
    throw new AxiosError(
      "Request failed with status code 500",
      "ERR_BAD_RESPONSE",
      config,
      undefined,
      response,
    );
  };
}

describe("REST notifications", () => {
  beforeEach(() => {
    noticeMocks.success.mockReset();
    noticeMocks.error.mockReset();
  });

  afterEach(() => {
    vi.restoreAllMocks();
  });

  it("allows one request to leave success feedback to its caller", async () => {
    await restInstance.post("/settings", {}, {
      maboxNotify: false,
      adapter: responseAdapter({ success: true, message: "保存成功" }),
    });

    expect(noticeMocks.success).not.toHaveBeenCalled();
  });

  it("allows the same request owner to handle a WP REST 500 error", async () => {
    const consoleSpy = vi.spyOn(console, "error").mockImplementation(() => {});

    await expect(restInstance.post("/settings", {}, {
      maboxNotify: false,
      adapter: errorAdapter({
        code: "rest_save_failed",
        message: "保存失败，已恢复为之前的设置",
        data: { status: 500 },
      }),
    })).rejects.toBeInstanceOf(AxiosError);

    expect(noticeMocks.error).not.toHaveBeenCalled();
    expect(consoleSpy).not.toHaveBeenCalled();
  });

  it("keeps the existing success feedback for other REST requests", async () => {
    await restInstance.post("/other-action", {}, {
      adapter: responseAdapter({ success: true, message: "操作完成" }),
    });

    expect(noticeMocks.success).toHaveBeenCalledTimes(1);
    expect(noticeMocks.success).toHaveBeenCalledWith("操作完成");
  });

  it("keeps the existing error feedback for other REST requests", async () => {
    const consoleSpy = vi.spyOn(console, "error").mockImplementation(() => {});

    await expect(restInstance.post("/other-action", {}, {
      adapter: errorAdapter({
        code: "rest_action_failed",
        message: "操作失败",
        data: { status: 500 },
      }),
    })).rejects.toBeInstanceOf(AxiosError);

    expect(noticeMocks.error).toHaveBeenCalledTimes(1);
    expect(noticeMocks.error).toHaveBeenCalledWith("出错：操作失败");
    expect(consoleSpy).toHaveBeenCalledWith("操作失败");
  });
});
