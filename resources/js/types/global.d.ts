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
                show_search: boolean;
                show_add_room: boolean;
                show_send_icon: boolean;
                show_files: boolean;
                show_audio: boolean;
                audio_bit_rate: number;
                audio_sample_rate: number;
                show_emojis: boolean;
                show_reaction_emojis: boolean;
                show_new_messages_divider: boolean;
                show_footer: boolean;
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
