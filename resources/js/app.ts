import "../css/app.css";

import { createApp } from "vue";
import env from "./plugins/env";
import pinia from "./plugins/pinia";
import SupportChat from "./components/SupportChat.vue";

import.meta.glob("../images/**");

createApp({}).component("support-chat", SupportChat).use(env).use(pinia).mount("#support-chat");
