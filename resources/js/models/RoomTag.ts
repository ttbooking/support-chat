import { Model } from "pinia-orm";
import { Attr, Str, BelongsTo } from "pinia-orm/decorators";
import Room from "@/models/Room";
import type { RoomTag as BaseTag } from "@/types";

export default class RoomTag extends Model implements BaseTag {
    static entity = "roomTags";

    static primaryKey = ["roomId", "name", "type"];

    @Attr() declare roomId: string;
    @Attr() declare name: string;
    @Str("") declare type: string;
    @Str("") declare link: string;

    @BelongsTo(() => Room, "roomId", "roomId") declare room: Room;
}
