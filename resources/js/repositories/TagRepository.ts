import { AxiosRepository } from "@pinia-orm/axios";
import RoomTag from "@/models/RoomTag";

export default class TagRepository extends AxiosRepository<RoomTag> {
    use = RoomTag;

    static cursor = null;

    fetch = async (search?: string, fresh = false) => {
        if (fresh) TagRepository.cursor = null;
        const response = await this.api().get(window.SupportChat.path + "/api/tags", {
            dataKey: "data",
            params: { search, cursor: TagRepository.cursor },
        });
        TagRepository.cursor = response.response.data.meta.next_cursor;
        return response;
    };
}
