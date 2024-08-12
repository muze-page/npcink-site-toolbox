import Compose from "@/components/shortcode/compose";
import Pendant from "@/components/shortcode/pendant";

const App: React.FC = () => {
  return (
    <>
      <div className="describe">
        启用对应短代码后，在经典编辑器中，有短代码下拉框可供选择，古登堡编辑器中，有魔法短代码区块可供选择
      </div>
      <Compose />
      <Pendant />
    </>
  );
};

export default App;
