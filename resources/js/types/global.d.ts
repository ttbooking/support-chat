import { AxiosInstance } from "axios";
import Echo from "laravel-echo";
import en from "../locales/en.json";
import Room from "@/models/Room";
import type { WinBoxProps, WinBoxModel } from "@/types/winbox";

declare global {
    interface Window {
        chat: {
            path: string;
            userId: string;
            filter?: string;
            roomId?: string;
            permissions: {
                viewForeignRooms: boolean;
            };
            windowDefaults: Partial<WinBoxProps & WinBoxModel>;
            features: {
                showSearch?: boolean;
                showAddRoom?: boolean;
                showSendIcon?: boolean;
                showFiles?: boolean;
                showAudio?: boolean;
                audioBitRate?: number;
                audioSampleRate?: number;
                showEmojis?: boolean;
                showReactionEmojis?: boolean;
                showNewMessagesDivider?: boolean;
                showFooter?: boolean;
            };
            styles: {
                light: Record<string, Record<string, string>>;
                dark: Record<string, Record<string, string>>;
            };
            open?: () => void;
        };
        chatHandlers?: {
            roomInfo?: (room: Room) => void;
        };
        axios: AxiosInstance;
        Echo: Echo<"pusher">;
    }
}

declare module "vue" {
    interface ComponentCustomProperties {
        $env: typeof window.chat & typeof window.chatHandlers;
    }
}

declare module "vue-i18n" {
    type MessageSchema = typeof en;

    interface DefineLocaleMessage extends MessageSchema {}
}
