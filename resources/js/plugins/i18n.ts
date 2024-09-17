import { createI18n, type I18nOptions } from "vue-i18n";
import messages from "@intlify/unplugin-vue-i18n/messages";

const options: I18nOptions = {
    locale: navigator.language,
    fallbackLocale: "en",
    legacy: false,
    messages,
};

export default createI18n<false, typeof options>(options);
