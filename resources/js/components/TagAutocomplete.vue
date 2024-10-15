<template>
    <v-autocomplete
        v-model="pickedTags"
        v-model:search="search"
        multiple
        chips
        closable-chips
        :items="tags"
        item-title="name"
        return-object
    >
        <template #chip="{ props, item }">
            <v-chip v-bind="props" variant="flat" :color="tagColor(item.raw)" />
        </template>
        <template #append-item>
            <v-list-item v-intersect="onIntersect">Loading...</v-list-item>
        </template>
    </v-autocomplete>
</template>

<script setup lang="ts">
import { ref, computed, watchEffect } from "vue";
import stc from "string-to-color";
import { useRepo } from "pinia-orm";
import { useSortBy } from "pinia-orm/helpers";
import TagRepository from "@/repositories/TagRepository";
import type { Tag } from "@/types";

const pickedTags = defineModel<Tag[]>({ default: [] });

const search = ref<string>("");

const tagRepo = computed(() => useRepo(TagRepository));
const tags = computed(() =>
    useSortBy(
        tagRepo.value.orderBy("name").get(),
        (tag) => !pickedTags.value.map((tag) => tag.name).includes(tag.name),
    ),
);

const tagColor = (tag: Tag) => stc(tag.type ?? tag.name.replace(/ .*/, ""));

watchEffect(async () => {
    await tagRepo.value.fetch(search.value);
});

async function onIntersect(isIntersecting: boolean) {
    if (isIntersecting) await tagRepo.value.fetch(search.value);
}
</script>
