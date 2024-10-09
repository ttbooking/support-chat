import { Model } from "pinia-orm";
import { Attr, BelongsToMany } from "pinia-orm/decorators";
import Room from "./Room";
import RoomTag from "./RoomTag";
import type { Tag as BaseTag } from "@/types";

export default class Tag extends Model implements BaseTag {
    static entity = "tags";

    static primaryKey = "tag";

    @Attr() declare tag: string;

    @BelongsToMany(() => Room, () => RoomTag, "tag", "roomId") declare rooms: Room[];
}
