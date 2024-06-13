/**
 * 生成二维码
 * @returns
 */
import { QRCode } from "antd";
import WeXin from "@/assets/share/微信.svg";
import { publicShareData } from "@/store/index";
const App: React.FC = () => {
  //准备当前网页链接
  const page_url = encodeURIComponent(publicShareData.page.url);
  return (
    <>
      <div className="qrBox">
        <div className="qr">
          <QRCode
            errorLevel="H"
            value={page_url}
            icon={WeXin}
            style={{ border: "0px" }}
          />
          <span>微信扫一扫浏览本页</span>
        </div>
      </div>
    </>
  );
};
export default App;
