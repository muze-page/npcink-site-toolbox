import React from "react";
import { Card, Row, Col, Typography, Button, Space } from "antd";
import { CheckOutlined } from "@ant-design/icons";
import { Preset } from "@/tool/presets";

const { Title, Paragraph } = Typography;

interface StepSelectProps {
  presets: Preset[];
  onSelect: (preset: Preset) => void;
}

const StepSelect: React.FC<StepSelectProps> = ({ presets, onSelect }) => {
  return (
    <div>
      <Paragraph type="secondary" style={{ marginBottom: 16, textAlign: "center" }}>
        选择最符合您站点用途的场景，我们将为您推荐合适的配置方案。
      </Paragraph>
      <Row gutter={[16, 16]}>
        {presets.map((preset) => (
          <Col xs={24} sm={8} key={preset.id}>
            <Card
              hoverable
              className="h-full"
              style={{ textAlign: "center" }}
              onClick={() => onSelect(preset)}
            >
              <Space direction="vertical" size="middle" className="w-full">
                <div style={{ fontSize: 40 }}>{preset.icon || "📋"}</div>
                <Title level={5} style={{ margin: 0 }}>{preset.name}</Title>
                <Paragraph type="secondary" style={{ fontSize: 13, margin: 0, minHeight: 40 }}>
                  {preset.description}
                </Paragraph>
                <Button type="primary" block icon={<CheckOutlined />}>
                  选择此方案
                </Button>
              </Space>
            </Card>
          </Col>
        ))}
      </Row>
    </div>
  );
};

export default StepSelect;
