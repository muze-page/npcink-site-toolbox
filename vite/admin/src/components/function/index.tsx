import Auxiliary from "@/components/function/auxiliary";
import Seo from "@/components/function/seo";
import Tips from "@/components/function/tips";

const App: React.FC = () => {
  return (
    <div className="mabox-app-center">
      <Tips />
      <Seo />
      <Auxiliary />
    </div>
  );
};

export default App;
