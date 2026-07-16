import { afterEach, beforeEach, describe, expect, it, vi } from "vitest";

import { notice } from "@/tool/notice";

describe("notice", () => {
  beforeEach(() => {
    vi.useFakeTimers();
    document.body.innerHTML = "";
  });

  afterEach(() => {
    vi.runOnlyPendingTimers();
    vi.useRealTimers();
    document.body.innerHTML = "";
  });

  it("renders message text without interpreting HTML", () => {
    notice.success('<img src=x onerror="alert(1)">操作完成');

    const visibleNotice = document.querySelector<HTMLElement>(".mabox-notice");
    const politeAnnouncer = document.querySelector<HTMLElement>(
      ".mabox-notice-announcer--polite",
    );
    expect(visibleNotice).toHaveTextContent('<img src=x onerror="alert(1)">操作完成');
    expect(visibleNotice?.querySelector("img")).toBeNull();
    expect(visibleNotice).not.toHaveAttribute("role");
    expect(politeAnnouncer).toBeEmptyDOMElement();

    vi.advanceTimersByTime(0);

    expect(politeAnnouncer).toHaveTextContent('<img src=x onerror="alert(1)">操作完成');
    expect(politeAnnouncer?.querySelector("img")).toBeNull();
    expect(politeAnnouncer).toHaveAttribute("role", "status");
    expect(politeAnnouncer).toHaveAttribute("aria-live", "polite");
  });

  it("uses assertive alert semantics for errors", () => {
    notice.error("保存失败");

    const visibleNotice = document.querySelector(".mabox-notice");
    const alert = document.querySelector('[role="alert"]');
    expect(visibleNotice).toHaveTextContent("保存失败");
    expect(visibleNotice).not.toHaveAttribute("role");
    expect(alert).toBeEmptyDOMElement();

    vi.advanceTimersByTime(0);

    expect(alert).toHaveTextContent("保存失败");
    expect(alert).toHaveAttribute("aria-live", "assertive");
    expect(alert).toHaveAttribute("aria-atomic", "true");
  });

  it("uses a blue polite status for informational messages", () => {
    notice.info("所有服务可达，无需修复");

    const status = document.querySelector('[role="status"]');
    const visibleNotice = document.querySelector(".mabox-notice--info");
    expect(status).toBeEmptyDOMElement();
    expect(visibleNotice).toHaveTextContent("所有服务可达，无需修复");
    expect(visibleNotice).not.toHaveAttribute("role");

    vi.advanceTimersByTime(0);

    expect(status).toHaveTextContent("所有服务可达，无需修复");
    expect(status).toHaveAttribute("aria-live", "polite");
    expect(status).toHaveAttribute("aria-atomic", "true");
  });

  it("keeps only the three newest notices", () => {
    notice.success("第一条");
    notice.warning("第二条");
    notice.error("第三条");
    notice.success("第四条");

    const messages = Array.from(document.querySelectorAll(".mabox-notice"));
    expect(messages).toHaveLength(3);
    expect(messages.map((element) => element.textContent)).toEqual([
      "第二条",
      "第三条",
      "第四条",
    ]);
  });

  it("removes notices and the empty stack after about two seconds", () => {
    notice.warning("即将消失");
    const politeAnnouncer = document.querySelector(".mabox-notice-announcer--polite");

    vi.advanceTimersByTime(1999);
    expect(document.querySelector(".mabox-notice")).not.toBeNull();

    vi.advanceTimersByTime(1);
    expect(document.querySelector(".mabox-notice")).toBeNull();
    expect(document.querySelector(".mabox-notice-stack")).toBeNull();
    expect(document.querySelector(".mabox-notice-announcer--polite")).toBe(politeAnnouncer);
    expect(politeAnnouncer).toBeInTheDocument();
  });
});
