import { AxiosInstance } from "axios";
import Echo from "laravel-echo";
import { PusherPresenceChannel } from "laravel-echo/dist/channel";

declare global {
    interface Window {
        SupportChat: { path: string };
        axios: AxiosInstance;
        Echo: Echo;
        roomChannel: PusherPresenceChannel;
    }
}
