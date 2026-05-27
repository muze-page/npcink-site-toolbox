import React from "react";
import { Result, Button, Typography } from "antd";
import { CheckCircleOutlined, SettingOutlined, DashboardOutlined } from "@ant-design/icons";

const { Text } = Typography;

interface StepApplyProps {
  onFinish: () => void;
  onReconfigure: () => void;
}

const StepApply: React.FC<StepApplyProps> = ({ onFinish, onReconfigure }) => {
  return (
    <div style={{ textAlign: "center", padding: "24px 0" }}>
      <Result
        icon={<CheckCircleOutlined style={{ color: "#52c41a" }} />}
        title="配置方案已应用！"
        subTitle="推荐配置已生效，您可以随时在设置页面调整。下一步建议查看体检中心了解站点状态。"
        extra={[
          <Button type="primary" key="dashboard" icon={<DashboardOutlined />} onClick={onFinish}>
            前往体检中心
          </Button>,
          <Button key="reconfigure" icon={<SettingOutlined />} onClick={onReconfigure}>
            重新配置
          </Button>,
        ]}
      />
      <Text type="secondary" style={{ fontSize: 12 }}>
        您可以随时在 Dashboard 的"一键配置方案"中切换方案
      </Text>
    </div>
  );
};

export default StepApply;