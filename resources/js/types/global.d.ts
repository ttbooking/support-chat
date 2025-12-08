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
                showSearch: boolean;
                showAddRoom: boolean;
                showSendIcon: boolean;
                showFiles: boolean;
                showAudio: boolean;
                audioBitRate: number;
                audioSampleRate: number;
                showEmojis: boolean;
                showReactionEmojis: boolean;
                showNewMessagesDivider: boolean;
                showFooter: boolean;
            };
            styles: {
                light: Record<string, Record<string, string>>;
                dark: Record<string, Record<string, string>>;
            };
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
