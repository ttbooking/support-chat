import "../css/app.css";

import { createApp } from "vue";
import { createPinia } from "pinia";
import SupportChat from "./components/SupportChat.vue";

import.meta.glob("../images/**");

createApp({}).component("support-chat", SupportChat).use(createPinia()).mount("#support-chat");
