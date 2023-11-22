//权限管理
import Disable from "@/components/authority/disable";
import Auxiliary from "@/components/authority/auxiliary";
import B2 from "@/components/authority/b2";
import Wx_xcx_link from "@/components/authority/wx_xcx_link";
import DownDatabase from "@/components/authority/down_database";
const App: React.FC = () => {
  return (
    <>
      {/**下载指定数据库表内容 */}
      <DownDatabase />
      {/**禁用更新 */}
      <Disable />
      {/**辅助功能 */}
      <Auxiliary />
      {/**微信小程序链接 */}
      <Wx_xcx_link />
      {/**B2功能 */}
      <B2 />
    </>
  );
};

export default App;
