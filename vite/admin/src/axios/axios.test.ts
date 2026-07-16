import { beforeEach, describe, expect, it, vi } from "vitest";

const restMocks = vi.hoisted(() => ({
  get: vi.fn(),
}));

vi.mock("@/axios/public", () => ({
  restInstance: restMocks,
}));

import * as categoryApi from "@/axios/axios";

describe("category data API", () => {
  beforeEach(() => {
    restMocks.get.mockReset();
  });

  it("unwraps the standard REST data envelope", async () => {
    const data = {
      categorys: [{ label: "新闻", value: 2 }],
      tags: [{ label: "WordPress", value: 3 }],
      pages: [{ label: "关于", value: 4 }],
    };
    restMocks.get.mockResolvedValue({ success: true, data });

    await expect(categoryApi.getCategoryData()).resolves.toEqual(data);
    expect(restMocks.get).toHaveBeenCalledWith("/tools/categories");
  });

  it("rejects malformed option values instead of passing them to Select", async () => {
    restMocks.get.mockResolvedValue({
      success: true,
      data: {
        categorys: [{ label: "新闻", value: "2" }],
        tags: [],
        pages: [],
      },
    });

    await expect(categoryApi.getCategoryData()).rejects.toThrow("分类数据格式无效");
  });

  it("accepts an empty site without treating it as an API failure", async () => {
    const data = { categorys: [], tags: [], pages: [] };
    restMocks.get.mockResolvedValue({ success: true, data });

    await expect(categoryApi.getCategoryData()).resolves.toEqual(data);
  });

  it("does not expose the retired table export helpers", () => {
    expect(categoryApi).not.toHaveProperty("get_all_table_name");
    expect(categoryApi).not.toHaveProperty("get_table_data");
  });
});
