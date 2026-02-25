<template>
    <v-autocomplete v-model="pickedTags" v-model:search="search" multiple chips closable-chips :items="tags">
        <template #chip="{ props, item }">
            <v-chip v-bind="props" variant="flat" :color="tagColor(item)" />
        </template>
        <template #append-item>
            <v-list-item v-intersect="onIntersect">Loading...</v-list-item>
        </template>
    </v-autocomplete>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { watchDebounced } from "@vueuse/core";
import stc from "string-to-color";
import { useRepo } from "pinia-orm";
import { useSortBy } from "pinia-orm/helpers";
import TagRepository from "@/repositories/TagRepository";

const pickedTags = defineModel<string[]>({ default: [] });

const search = ref<string>("");

const tagRepo = useRepo(TagRepository);
const tags = computed(() =>
    useSortBy(tagRepo.orderBy("link").get(), (tag) => !pickedTags.value.includes(tag.link)).map((tag) => tag.link),
);

const tagColor = (tag: string) => stc(tag.replace(/[ :][^:]*$/, ""));

watchDebounced(
    search,
    async () => {
        await tagRepo.fetch(search.value);
    },
    { debounce: 500, maxWait: 1000 },
);

async function onIntersect(isIntersecting: boolean) {
    if (isIntersecting) await tagRepo.fetch(search.value);
}
</script>
