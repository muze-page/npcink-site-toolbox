import React from "react";
import { DatePicker } from "antd";
import dayjs, { type Dayjs } from "dayjs";

const DATE_FORMAT = "YYYY-MM-DD HH:mm";

type RangePickerProps = React.ComponentProps<typeof DatePicker.RangePicker>;

type TimePeriodProps = Omit<
  RangePickerProps,
  "components" | "defaultValue" | "format" | "onChange" | "showTime" | "value"
> & {
  value?: readonly string[] | null;
  onChange?: (value: string[]) => void;
};

type AccessibleDateInputProps = React.InputHTMLAttributes<HTMLInputElement> & {
  "date-range"?: "start" | "end";
};

const AccessibleDateInput = React.forwardRef<
  HTMLInputElement,
  AccessibleDateInputProps
>(({ "aria-label": rangeLabel, "date-range": rangePart, ...inputProps }, ref) => {
  const partLabel = rangePart === "end" ? "结束时间" : "开始时间";
  const accessibleLabel = rangeLabel ? `${rangeLabel}：${partLabel}` : partLabel;

  return <input {...inputProps} ref={ref} aria-label={accessibleLabel} />;
});

AccessibleDateInput.displayName = "AccessibleDateInput";

const toPickerValue = (
  value: readonly string[] | null | undefined,
): [Dayjs, Dayjs] | null => {
  if (!Array.isArray(value) || value.length !== 2) {
    return null;
  }

  const start = dayjs(value[0]);
  const end = dayjs(value[1]);

  return start.isValid() && end.isValid() ? [start, end] : null;
};

const normalizeIds = (id: RangePickerProps["id"]): RangePickerProps["id"] =>
  typeof id === "string" ? { start: id, end: `${id}-end` } : id;

const TimePeriod = React.forwardRef<
  React.ElementRef<typeof DatePicker.RangePicker>,
  TimePeriodProps
>(
  (
    {
      id,
      onChange,
      placeholder = ["开始时间", "结束时间"],
      value,
      ...rangePickerProps
    },
    ref,
  ) => (
    <DatePicker.RangePicker
      {...rangePickerProps}
      ref={ref}
      components={{ input: AccessibleDateInput }}
      format={DATE_FORMAT}
      id={normalizeIds(id)}
      placeholder={placeholder}
      showTime={{ format: "HH:mm" }}
      value={toPickerValue(value)}
      onChange={(dates, dateStrings) => {
        if (!dates || dateStrings.some((dateString) => !dateString)) {
          onChange?.([]);
          return;
        }

        onChange?.([dateStrings[0], dateStrings[1]]);
      }}
    />
  ),
);

TimePeriod.displayName = "TimePeriod";

export default TimePeriod;
