import "../css/app.css";

import { Axios } from "axios";
import Echo from "laravel-echo";
import { PusherPresenceChannel } from "laravel-echo/dist/channel";
import { createApp } from "vue";
import { createPinia } from "pinia";
import SupportChat from "./components/SupportChat.vue";

declare global {
    interface Window {
        SupportChat: { path: string };
        axios: Axios;
        Echo: Echo;
        roomChannel: PusherPresenceChannel;
    }
}

createApp({})
    .component("support-chat", SupportChat)
    .use(createPinia())
    .mount("#support-chat");
