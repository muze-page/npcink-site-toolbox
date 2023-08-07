//聚合
import Site from "@/components/optimize/site";
import Medium from "@/components/optimize/medium";
import Comment from "@/components/optimize/comment";
import Other from "@/components/optimize/other";
import Secure from "@/components/optimize/secure";
const App: React.FC = () => {
  return (
    <>
      <Site />
      <Medium />
      <Comment />
      <Other />
      <Secure />
    </>
  );
};

export default App;
