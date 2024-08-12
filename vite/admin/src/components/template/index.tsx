/**
 * 页面模版
 */
import Static from "@/components/template/static";
import Trends from "@/components/template/trends";
const App: React.FC = () => {
  return (
    <>
      <div className="describe">启用对应模版后，在页面中可选择对应模版，部分模版提供选项</div>
      <Static />
      <Trends />
    </>
  );
};

export default App;
