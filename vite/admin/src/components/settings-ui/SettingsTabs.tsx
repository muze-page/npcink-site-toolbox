import { Suspense, useEffect, useState, type KeyboardEvent, type ReactNode } from "react";

export interface SettingsTab {
  key: string;
  label: string;
  prefixes?: readonly string[];
  content: ReactNode;
}

interface SettingsTabsProps {
  ariaLabel: string;
  idPrefix: string;
  tabs: readonly SettingsTab[];
  targetItemId?: string;
}

const TabPanelFallback = (
  <div className="mabox-view-state mabox-view-state--loading" role="status" aria-live="polite">
    <span className="mabox-view-state-spinner" aria-hidden="true" />
    <span className="mabox-view-state-copy">
      <strong>正在加载当前分组</strong>
      <span>首次打开时正在准备相关设置。</span>
    </span>
  </div>
);

const SettingsTabs = ({ ariaLabel, idPrefix, tabs, targetItemId }: SettingsTabsProps) => {
  const matchedTargetKey = targetItemId
    ? tabs.find((tab) => tab.prefixes?.some((prefix) => targetItemId.startsWith(prefix)))?.key
    : undefined;
  const [activeKey, setActiveKey] = useState(() => matchedTargetKey || tabs[0]?.key || "");
  const activeTab = tabs.find((tab) => tab.key === activeKey) || tabs[0];

  useEffect(() => {
    if (matchedTargetKey) setActiveKey(matchedTargetKey);
  }, [matchedTargetKey]);

  if (!activeTab) return null;

  const selectTab = (nextKey: string, moveFocus = false) => {
    setActiveKey(nextKey);
    if (!moveFocus) return;

    requestAnimationFrame(() => {
      const nextTab = document.getElementById(`${idPrefix}-tab-${nextKey}`);
      nextTab?.focus();
      nextTab?.scrollIntoView?.({ block: "nearest", inline: "nearest" });
    });
  };

  const handleTabKeyDown = (event: KeyboardEvent<HTMLButtonElement>, index: number) => {
    let nextIndex: number | null = null;
    if (event.key === "ArrowRight") nextIndex = (index + 1) % tabs.length;
    if (event.key === "ArrowLeft") nextIndex = (index - 1 + tabs.length) % tabs.length;
    if (event.key === "Home") nextIndex = 0;
    if (event.key === "End") nextIndex = tabs.length - 1;
    if (nextIndex === null) return;

    event.preventDefault();
    selectTab(tabs[nextIndex].key, true);
  };

  return (
    <>
      <div
        className="mabox-settings-tabs"
        role="tablist"
        aria-label={ariaLabel}
        aria-orientation="horizontal"
      >
        {tabs.map((tab, index) => (
          <button
            key={tab.key}
            id={`${idPrefix}-tab-${tab.key}`}
            type="button"
            role="tab"
            aria-controls={`${idPrefix}-panel`}
            aria-selected={activeTab.key === tab.key}
            tabIndex={activeTab.key === tab.key ? 0 : -1}
            className={`mabox-settings-tab ${activeTab.key === tab.key ? "mabox-settings-tab--active" : ""}`}
            onClick={() => selectTab(tab.key)}
            onKeyDown={(event) => handleTabKeyDown(event, index)}
          >
            {tab.label}
          </button>
        ))}
      </div>
      <div
        id={`${idPrefix}-panel`}
        className="mabox-settings-panel"
        role="tabpanel"
        aria-labelledby={`${idPrefix}-tab-${activeTab.key}`}
      >
        <Suspense fallback={TabPanelFallback}>{activeTab.content}</Suspense>
      </div>
    </>
  );
};

export default SettingsTabs;
