import type { Receive } from "./components/tool/interface";

declare global {
  interface Window {
    dataLocal: Receive | "";
  }
}

export {};
