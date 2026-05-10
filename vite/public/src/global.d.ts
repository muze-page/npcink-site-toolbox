import type { PublicShareData } from "./store/interface";

declare global {
  interface Window {
    dataLocal: PublicShareData | "";
  }
}

export {};
