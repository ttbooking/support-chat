import { Model } from "pinia-orm";
import { Attr, Str, BelongsToMany } from "pinia-orm/decorators";
import Room from "./Room";
import RoomTag from "./RoomTag";
import type { Tag as BaseTag } from "@/types";

export default class Tag extends Model implements BaseTag {
    static entity = "tags";

    static primaryKey = "name";

    @Attr() declare name: string;
    @Str(null) declare type?: string;

    @BelongsToMany(() => Room, () => RoomTag, "tagName", "roomId") declare rooms: Room[];
}
