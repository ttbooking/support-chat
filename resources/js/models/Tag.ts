import { Model } from "pinia-orm";
import { Attr, Str, BelongsToMany } from "pinia-orm/decorators";
import Room from "./Room";
import RoomTag from "./RoomTag";
import type { Tag as BaseTag } from "@/types";

export default class Tag extends Model implements BaseTag {
    static entity = "tags";

    static primaryKey = "id";

    @Attr() declare id: number;
    @Attr() declare name: string;
    @Str("") declare type: string;
    @Str("") declare link: string;

    @BelongsToMany(() => Room, () => RoomTag, "tagId", "roomId") declare rooms: Room[];
}
