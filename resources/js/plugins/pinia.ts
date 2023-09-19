import { createPinia } from "pinia";
import { createORM } from "pinia-orm";
import { createPiniaOrmAxios } from "@pinia-orm/axios";

const pinia = createPinia();
const piniaOrm = createORM();

piniaOrm().use(
    createPiniaOrmAxios({
        axios: window.axios,
        baseURL: window.SupportChat.path + "/api/v1",
        dataKey: "data",
    }),
);

export default pinia.use(piniaOrm);
