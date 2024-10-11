import { createPinia } from "pinia";
import { createORM } from "pinia-orm";
import { createPiniaOrmAxios } from "@pinia-orm/axios";

export default createPinia().use(
    createORM({
        plugins: [
            createPiniaOrmAxios({
                axios: window.axios,
                baseURL: window.SupportChat.path,
                dataKey: "data",
            }),
        ],
    }),
);
