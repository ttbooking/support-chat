import { AxiosInstance } from "axios";
import Echo from "laravel-echo";
import en from "../locales/en.json";

declare global {
    interface Window {
        SupportChat: {
            path: string;
            userId: string;
            roomId?: string;
        };
        axios: AxiosInstance;
        Echo: Echo<"pusher">;
    }
}

declare module "vue" {
    interface ComponentCustomProperties {
        $env: typeof window.SupportChat;
    }
}

declare module "vue-i18n" {
    type MessageSchema = typeof en;

    interface DefineLocaleMessage extends MessageSchema {}
}
