import "../css/app.css";

import { createApp } from "vue";
import { createPinia } from "pinia";
import { createORM } from "pinia-orm";
import env from "./plugins/env";
import SupportChat from "./components/SupportChat.vue";

import.meta.glob("../images/**");

createApp({})
    .component("support-chat", SupportChat)
    .use(createPinia().use(createORM()))
    .use(env)
    .mount("#support-chat");
