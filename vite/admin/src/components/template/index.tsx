/**
 * 页面模版
 */
import Static from "@/components/template/static";
import Trends from "@/components/template/trends";
const App: React.FC = () => {
  return (
    <>
      <div className="describe">添加各种可自定义的页面模版</div>
      <Static />
      <Trends />
    </>
  );
};

export default App;
