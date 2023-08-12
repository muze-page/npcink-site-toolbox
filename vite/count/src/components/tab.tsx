import {  useState } from "react";

const Tab = () => {
  const [activeTab, setActiveTab] = useState(0);

  const tabs = [
    {
      title: "Tab 1",
      content: "Content of Tab 1",
    },
    {
      title: "Tab 2",
      content: "Content of Tab 2",
    },
    {
      title: "Tab 3",
      content: "Content of Tab 3",
    },
  ];

  const handleTabClick = (index:number) => {
    setActiveTab(index);
  };

  return (
    <div className="tab">
      <div className="tab-header">
        {tabs.map((tab, index) => (
          <button
            key={index}
            className={`tab-button ${activeTab === index ? "active" : ""}`}
            onClick={() => handleTabClick(index)}
          >
            {tab.title}
          </button>
        ))}
      </div>
      <div className="tab-content">
        {tabs.map((tab, index) => (
          <div
            key={index}
            className={`tab-item ${activeTab === index ? "active" : ""}`}
          >
            {tab.content}
          </div>
        ))}
      </div>
    </div>
  );
};

export default Tab;
