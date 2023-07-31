import React from "react";
import { useState, useEffect } from "react";
import { Switch } from "antd";

const App: React.FC = () => {
    const [contentSwitch, setContentSwitch] = useState<boolean>(
      import.meta.env.VITE_OPTION_HANDLE === "true"
    );
  
    const handleSwitchChange = (checked: boolean) => {
      setContentSwitch(checked);
    };
  
    useEffect(() => {
      // 监听 window 对象上的 dataLocal 变化，并更新 contentSwitch 状态
      const handleDataLocalChange = () => {
        setContentSwitch(import.meta.env.VITE_OPTION_HANDLE === "true");
      };
  
      window.addEventListener("message", handleDataLocalChange);
  
      return () => {
        window.removeEventListener("message", handleDataLocalChange);
      };
    }, []);
  
    return (
      <>
        控制顶部文本显示
        <Switch defaultChecked={contentSwitch} onChange={handleSwitchChange} />
        {contentSwitch.toString()}
      </>
    );
  };
  
  export default App;
