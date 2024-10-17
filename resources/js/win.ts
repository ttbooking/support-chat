import "../css/app.css";

import { createApp } from "vue";
import Win from "./Win.vue";
import env from "./plugins/env";
import i18n from "./plugins/i18n";
import pinia from "./plugins/pinia";
import vuetify from "./plugins/vuetify";

//import.meta.glob("../images/**");

createApp(Win).use(env).use(i18n).use(pinia).use(vuetify).mount("#windowed-chat");
