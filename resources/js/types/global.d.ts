import { AxiosInstance } from "axios";
import Echo from "laravel-echo";
import { PusherPresenceChannel } from "laravel-echo/dist/channel";

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

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        $env: typeof window.SupportChat;
    }
}
