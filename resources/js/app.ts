import "../css/app.css";

import { createApp } from "vue";
import env from "./plugins/env";
import pinia from "./plugins/pinia";
import vuetify from "./plugins/vuetify";
import SupportChat from "./components/SupportChat.vue";

//import.meta.glob("../images/**");

createApp({}).component("support-chat", SupportChat).use(env).use(pinia).use(vuetify).mount("#support-chat");
