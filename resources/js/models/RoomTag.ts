import { Model } from "pinia-orm";
import { Attr } from "pinia-orm/decorators";

export default class RoomTag extends Model {
    static entity = "roomTag";

    static primaryKey = ["roomId", "tagName"];

    @Attr() declare roomId: string;
    @Attr() declare tagName: string;
}
