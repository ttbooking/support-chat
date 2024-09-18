import "vuetify/styles";
import { createVuetify } from "vuetify";
import { ru, en } from "vuetify/locale";

export default createVuetify({
    locale: {
        locale: "ru",
        fallback: "en",
        messages: { ru, en },
    },
});
