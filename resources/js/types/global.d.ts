import { AxiosInstance } from "axios";
import Echo from "laravel-echo";
import { PusherPresenceChannel } from "laravel-echo/dist/channel";
import en from "../../../lang/en.json";

declare global {
    interface Window {
        SupportChat: {
            userId: string;
            path: string;
        };
        axios: AxiosInstance;
        Echo: Echo;
        roomChannel: PusherPresenceChannel;
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
