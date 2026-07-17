import { createRef, type ElementRef } from "react";
import { Button, DatePicker, Form } from "antd";
import {
  act,
  cleanup,
  fireEvent,
  render,
  screen,
  waitFor,
} from "@testing-library/react";
import { afterEach, describe, expect, it, vi } from "vitest";

import TimePeriod from "@/basic/timeInput";

afterEach(cleanup);

describe("TimePeriod", () => {
  it("保留 Form 的标签和说明关系，并区分开始与结束时间", () => {
    render(
      <Form
        initialValues={{ countdown: ["2026-07-17 09:00", "2026-07-18 18:30"] }}
      >
        <Form.Item label="倒计时" name="countdown" extra="此时间段内才会显示内容">
          <TimePeriod />
        </Form.Item>
      </Form>,
    );

    const start = screen.getByRole("textbox", { name: "开始时间" });
    const end = screen.getByRole("textbox", { name: "结束时间" });
    const label = screen.getByText("倒计时").closest("label");
    const description = screen.getByText("此时间段内才会显示内容");

    expect(start).toHaveValue("2026-07-17 09:00");
    expect(end).toHaveValue("2026-07-18 18:30");
    expect(start).toHaveAttribute("id", "countdown");
    expect(end).toHaveAttribute("id", "countdown-end");
    expect(label).toHaveAttribute("for", start.id);
    expect(start).toHaveAttribute("aria-describedby", description.id);
    expect(end).toHaveAttribute("aria-describedby", description.id);
  });

  it("外部值变化和非数组旧值都能同步为受控显示", () => {
    const { rerender } = render(
      <TimePeriod value={["2026-07-17 09:00", "2026-07-18 18:30"]} />,
    );

    expect(screen.getByRole("textbox", { name: "开始时间" })).toHaveValue(
      "2026-07-17 09:00",
    );
    expect(screen.getByRole("textbox", { name: "结束时间" })).toHaveValue(
      "2026-07-18 18:30",
    );

    rerender(<TimePeriod value={["2026-08-01 08:15", "2026-08-02 20:45"]} />);

    expect(screen.getByRole("textbox", { name: "开始时间" })).toHaveValue(
      "2026-08-01 08:15",
    );
    expect(screen.getByRole("textbox", { name: "结束时间" })).toHaveValue(
      "2026-08-02 20:45",
    );

    rerender(<TimePeriod value={0 as never} />);

    expect(screen.getByRole("textbox", { name: "开始时间" })).toHaveValue("");
    expect(screen.getByRole("textbox", { name: "结束时间" })).toHaveValue("");
  });

  it("清空时间范围时输出空数组", () => {
    const onChange = vi.fn();
    const { container } = render(
      <TimePeriod
        value={["2026-07-17 09:00", "2026-07-18 18:30"]}
        onChange={onChange}
      />,
    );

    const clearButton = container.querySelector<HTMLElement>(".ant-picker-clear");
    expect(clearButton).not.toBeNull();

    fireEvent.mouseDown(clearButton as HTMLElement);
    fireEvent.click(clearButton as HTMLElement);

    expect(onChange).toHaveBeenCalledWith([]);
  });

  it("透传禁用、说明、错误、样式、失焦和 ref 契约", () => {
    const onBlur = vi.fn();
    const ref = createRef<ElementRef<typeof DatePicker.RangePicker>>();
    const { container, rerender } = render(
      <TimePeriod
        ref={ref}
        aria-describedby="time-help"
        aria-invalid="true"
        aria-label="维护倒计时"
        className="custom-time-range"
        disabled
        onBlur={onBlur}
        value={["2026-07-17 09:00", "2026-07-18 18:30"]}
      />,
    );

    const start = screen.getByRole("textbox", { name: "维护倒计时：开始时间" });
    const end = screen.getByRole("textbox", { name: "维护倒计时：结束时间" });

    expect(container.querySelector(".custom-time-range")).not.toBeNull();
    expect(start).toBeDisabled();
    expect(end).toBeDisabled();
    expect(start).toHaveAttribute("aria-describedby", "time-help");
    expect(end).toHaveAttribute("aria-describedby", "time-help");
    expect(start).toHaveAttribute("aria-invalid", "true");
    expect(end).toHaveAttribute("aria-invalid", "true");
    expect(ref.current).not.toBeNull();

    rerender(
      <TimePeriod
        ref={ref}
        aria-label="维护倒计时"
        className="custom-time-range"
        onBlur={onBlur}
        value={["2026-07-17 09:00", "2026-07-18 18:30"]}
      />,
    );
    act(() => ref.current?.focus());
    expect(start).toHaveFocus();

    fireEvent.blur(start);
    expect(onBlur).toHaveBeenCalled();
  });

  it("把 Form 错误状态关联到两个时间输入框", async () => {
    render(
      <Form>
        <Form.Item
          label="倒计时"
          name="countdown"
          rules={[{ required: true, message: "请选择倒计时范围" }]}
        >
          <TimePeriod />
        </Form.Item>
        <Button htmlType="submit">保存</Button>
      </Form>,
    );

    fireEvent.click(screen.getByRole("button", { name: /保.*存/ }));

    const error = await screen.findByText("请选择倒计时范围");
    const start = screen.getByRole("textbox", { name: "开始时间" });
    const end = screen.getByRole("textbox", { name: "结束时间" });

    await waitFor(() => {
      expect(start).toHaveAttribute("aria-invalid", "true");
      expect(end).toHaveAttribute("aria-invalid", "true");
    });
    expect(start.getAttribute("aria-describedby")).toContain(
      error.parentElement?.id,
    );
    expect(end.getAttribute("aria-describedby")).toContain(
      error.parentElement?.id,
    );
  });
});
