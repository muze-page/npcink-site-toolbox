import React, { useContext, useState, useCallback } from "react";
import { Modal, Steps, message } from "antd";
import { RocketOutlined } from "@ant-design/icons";
import StepSelect from "./StepSelect";
import StepPreview from "./StepPreview";
import StepApply from "./StepApply";
import { Preset, getWizardPresets } from "@/tool/presets";
import { DataContext } from "@/tool/dataContext";
import { saveOption } from "@/axios/save";
import { restInstance } from "@/axios/public";
import { diffConfig, getDiffSummary } from "@/tool/diff";
import { createSnapshot } from "@/tool/snapshot";
import DiffModal from "@/components/diff-modal";
import { ConfigDiffItem } from "@/tool/interface";

interface WizardModalProps {
  open: boolean;
  onCancel: () => void;
  onComplete: () => void;
  onNavigate?: (tabKey: string, itemId: string) => void;
}

function deepMerge(target: any, source: any): any {
  if (source === null || typeof source !== "object") return source;
  if (target === null || typeof source !== "object") return source;
  const result = { ...target };
  Object.keys(source).forEach((key) => {
    if (source[key] && typeof source[key] === "object" && !Array.isArray(source[key])) {
      result[key] = deepMerge(result[key] || {}, source[key]);
    } else {
      result[key] = source[key];
    }
  });
  return result;
}

const WizardModal: React.FC<WizardModalProps> = ({ open, onCancel, onComplete, onNavigate }) => {
  const { optionData, updateOption, refreshOption, lastSavedOption } = useContext(DataContext);
  const [current, setCurrent] = useState(0);
  const [selectedPreset, setSelectedPreset] = useState<Preset | null>(null);
  const [applying, setApplying] = useState(false);
  const [diffVisible, setDiffVisible] = useState(false);
  const [diffs, setDiffs] = useState<ConfigDiffItem[]>([]);
  const [pendingMerged, setPendingMerged] = useState<any>(null);

  const wizardPresets = getWizardPresets();

  const handleSelectPreset = useCallback((preset: Preset) => {
    setSelectedPreset(preset);
    setCurrent(1);
  }, []);

  const handleBack = useCallback(() => {
    setCurrent((prev) => Math.max(0, prev - 1));
  }, []);

  const handleApply = useCallback(() => {
    if (!selectedPreset) return;
    const merged = deepMerge(JSON.parse(JSON.stringify(optionData)), selectedPreset.config);
    const changes = diffConfig(lastSavedOption, merged);
    const summary = getDiffSummary(changes);

    if (!summary.hasChanges) {
      message.info("当前配置与方案一致，无需更改");
      setCurrent(2);
      return;
    }

    setPendingMerged(merged);
    setDiffs(changes);
    setDiffVisible(true);
  }, [selectedPreset, optionData, lastSavedOption]);

  const handleDiffConfirm = useCallback(async () => {
    if (!pendingMerged || !selectedPreset) return;
    setDiffVisible(false);
    setApplying(true);
    try {
      createSnapshot(optionData);

      Object.keys(selectedPreset.config).forEach((father) => {
        if (typeof pendingMerged[father] === "object" && pendingMerged[father] !== null) {
          Object.keys(pendingMerged[father]).forEach((son) => {
            updateOption(father, son, pendingMerged[father][son]);
          });
        }
      });
      await saveOption(pendingMerged);
      await refreshOption();

      try {
        await restInstance.post("/settings/wizard-complete", { preset_id: selectedPreset.id });
      } catch (_) {}

      message.success(`已应用「${selectedPreset.name}」配置方案`);
      setCurrent(2);
    } catch (error) {
      message.error("应用配置方案失败，请重试");
    } finally {
      setApplying(false);
      setPendingMerged(null);
    }
  }, [pendingMerged, selectedPreset, optionData, updateOption, refreshOption]);

  const handleDiffCancel = useCallback(() => {
    setDiffVisible(false);
    setDiffs([]);
    setPendingMerged(null);
  }, []);

  const handleFinish = useCallback(() => {
    onComplete();
    onCancel();
    if (onNavigate) {
      onNavigate("0", "");
    }
  }, [onComplete, onCancel, onNavigate]);

  const handleReset = useCallback(() => {
    setCurrent(0);
    setSelectedPreset(null);
  }, []);

  const steps = [
    { title: "选择场景", icon: <RocketOutlined /> },
    { title: "预览功能", icon: <RocketOutlined /> },
    { title: "完成", icon: <RocketOutlined /> },
  ];

  return (
    <Modal
      title="首次配置向导"
      open={open}
      onCancel={onCancel}
      width={720}
      footer={null}
      destroyOnClose
    >
      <Steps current={current} items={steps} size="small" style={{ marginBottom: 24 }} />

      {current === 0 && (
        <StepSelect presets={wizardPresets} onSelect={handleSelectPreset} />
      )}

      {current === 1 && selectedPreset && (
        <StepPreview
          preset={selectedPreset}
          currentConfig={optionData}
          onApply={handleApply}
          onBack={handleBack}
          applying={applying}
        />
      )}

      {current === 2 && (
        <StepApply onFinish={handleFinish} onReconfigure={handleReset} />
      )}

      <DiffModal
        visible={diffVisible}
        diffs={diffs}
        onConfirm={handleDiffConfirm}
        onCancel={handleDiffCancel}
      />
    </Modal>
  );
};

export default WizardModal;
