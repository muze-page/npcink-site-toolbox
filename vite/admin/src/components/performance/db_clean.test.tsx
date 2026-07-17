import { cleanup, fireEvent, render, screen, waitFor, within } from "@testing-library/react";
import { afterEach, beforeEach, describe, expect, it, vi } from "vitest";

import DbClean from "@/components/performance/db_clean";
import { DataContext, emptySecretStatus } from "@/tool/dataContext";
import { defaultVarOption } from "@/tool/defaultVar";

const apiMocks = vi.hoisted(() => ({
  getDbStats: vi.fn(),
  previewDb: vi.fn(),
  cleanDb: vi.fn(),
}));

vi.mock("@/api", () => ({
  performanceApi: apiMocks,
}));

vi.mock("@/tool/notice", () => ({
  notice: {
    error: vi.fn(),
    success: vi.fn(),
  },
}));

function renderDbClean() {
  render(
    <DataContext.Provider
      value={{
        optionData: defaultVarOption,
        updateOption: vi.fn(),
        refreshOption: vi.fn(),
        lastSavedOption: defaultVarOption,
        setLastSavedOption: vi.fn(),
        secretStatus: emptySecretStatus(),
        secretChanges: {},
        setSecretChange: vi.fn(),
        clearSecretChanges: vi.fn(),
        settingsState: "ready",
        settingsError: null,
      }}
    >
      <DbClean />
    </DataContext.Provider>,
  );
}

const getComputedStyle = window.getComputedStyle.bind(window);

beforeEach(() => {
  vi.spyOn(window, "getComputedStyle").mockImplementation((element) => getComputedStyle(element));
  apiMocks.getDbStats.mockReset().mockResolvedValue({
    success: true,
    data: {
      revisions: 12,
      drafts: 3,
      spam: 2,
      transients: 7,
      db_size: "8 MB",
    },
  });
  apiMocks.previewDb.mockReset().mockResolvedValue({
    success: true,
    data: { affected: 12, dry_run: true },
  });
  apiMocks.cleanDb.mockReset().mockResolvedValue({ success: true, data: { deleted: 12, dry_run: false } });
});

afterEach(() => {
  cleanup();
  vi.restoreAllMocks();
});

describe("数据库清理操作链", () => {
  it("不提供全量清理入口，并要求每种数据先预览再清理", async () => {
    renderDbClean();

    expect(screen.queryByRole("button", { name: "预览清理" })).not.toBeInTheDocument();
    expect(screen.queryByRole("button", { name: "执行清理" })).not.toBeInTheDocument();

    fireEvent.click(screen.getByRole("button", { name: "查看统计" }));

    const revisionCount = await screen.findByText("12 条");
    const revisionRow = revisionCount.closest("tr");
    expect(revisionRow).not.toBeNull();

    const row = within(revisionRow as HTMLElement);
    const cleanButton = row.getByRole("button", { name: /清\s*理/ });
    expect(cleanButton).toBeDisabled();

    fireEvent.click(row.getByRole("button", { name: /预\s*览/ }));

    await waitFor(() => {
      expect(apiMocks.previewDb).toHaveBeenCalledWith("revisions");
      expect(cleanButton).toBeEnabled();
    });
  });
});
