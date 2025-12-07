import { AxiosInstance } from "axios";
import Echo from "laravel-echo";
import en from "../locales/en.json";

declare global {
    interface Window {
        SupportChat: {
            path: string;
            userId: string;
            roomId?: string;
            features: {
                show_audio: boolean;
                show_files: boolean;
                show_emojis: boolean;
                show_search: boolean;
            };
            styles?: Record<string, Record<string, string>>;
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
