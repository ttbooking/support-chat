import { Model } from "pinia-orm";
import { Attr } from "pinia-orm/decorators";

export default class RoomTag extends Model {
    static entity = "roomTag";

    static primaryKey = ["roomId", "tag"];

    @Attr() declare roomId: string;
    @Attr() declare tag: string;
}
