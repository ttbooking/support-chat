import { AxiosRepository } from "@pinia-orm/axios";
import Tag from "@/models/Tag";

export default class TagRepository extends AxiosRepository<Tag> {
    use = Tag;

    static cursor = null;

    fetch = async (search?: string, fresh = false) => {
        if (fresh) TagRepository.cursor = null;
        const response = await this.api().get("/tags", { params: { search, cursor: TagRepository.cursor } });
        TagRepository.cursor = response.response.data.meta.next_cursor;
        return response;
    };
}
