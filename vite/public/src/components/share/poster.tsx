/**
 * 生成海报
 * @returns
 */
import "./poster.css";
import DefaultImg from "@/assets/default/file-dark-1920x1280.jpg";
import { QRCode } from "antd";
const App: React.FC = () => {
  //准备当前网页链接
  const site_url = encodeURIComponent(window.location.href);

  return (
    <>
      <div className="poster">
        <div className="box">
          <div className="bg">
            <img src={DefaultImg} />
            <div className="meat">
              <p id="formattedDay">06</p>
              <p id="formattedDate">2024/06</p>
            </div>
          </div>
          <div className="content">
            <h2>关于 - ZAXU</h2>
            <div className="meat">
              Our CustomerHit it Off, Never Change.Hi, I’m Jony Zhang. I based
              in Shanghai, China. A freelance Graphic, UX, UI Designer and
              Website Develo...
            </div>
            <div className="qr">
              <QRCode errorLevel="H" value={site_url} size={150} style={{border:"0px"}} />
              <p>扫描二维码了解详情</p>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default App;
