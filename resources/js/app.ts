import "../css/app.css";

import { createApp } from "vue";
import App from "./App.vue";
import env from "./plugins/env";
import i18n from "./plugins/i18n";
import pinia from "./plugins/pinia";
import vuetify from "./plugins/vuetify";

createApp(App).use(env).use(i18n).use(pinia).use(vuetify).mount("#standalone-chat");
