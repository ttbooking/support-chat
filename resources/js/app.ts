import "../css/app.css";

import { createApp } from "vue";
import env from "./plugins/env";
import i18n from "./plugins/i18n";
import pinia from "./plugins/pinia";
import vuetify from "./plugins/vuetify";
import SupportChat from "./components/SupportChat.vue";

//import.meta.glob("../images/**");

createApp({}).component("support-chat", SupportChat).use(env).use(i18n).use(pinia).use(vuetify).mount("#support-chat");
