import { fireEvent, render, screen } from "@testing-library/react";
import { describe, expect, it, vi } from "vitest";

import FeatureSearch from "@/components/feature-search";

vi.mock("@/tool/favorites", () => ({
  isFavorite: vi.fn().mockReturnValue(false),
  toggleFavorite: vi.fn(),
}));

describe("FeatureSearch", () => {
  it("直接使用生成索引跳转到语义化视图", () => {
    const onNavigate = vi.fn();
    render(<FeatureSearch onNavigate={onNavigate} />);

    fireEvent.change(screen.getByRole("textbox", { name: "搜索功能或设置" }), {
      target: { value: "数据库清理" },
    });
    fireEvent.click(screen.getByRole("button", { name: "打开数据库清理优化" }));

    expect(onNavigate).toHaveBeenCalledWith("maintenance", "performance-db_clean-enabled");
  });

  it("登录安全搜索结果使用 canonical 设置行 id", () => {
    const onNavigate = vi.fn();
    render(<FeatureSearch onNavigate={onNavigate} />);

    fireEvent.change(screen.getByRole("textbox", { name: "搜索功能或设置" }), {
      target: { value: "作者枚举" },
    });
    fireEvent.click(screen.getByRole("button", { name: "打开限制匿名作者枚举" }));

    expect(onNavigate).toHaveBeenCalledWith(
      "china",
      "domestic-login_security-anonymous_author_guard_enabled",
    );
  });
});
