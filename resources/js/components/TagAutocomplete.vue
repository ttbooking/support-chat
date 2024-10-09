<template>
    <v-autocomplete
        v-model="pickedTags"
        v-model:search="search"
        multiple
        chips
        closable-chips
        :items="tags"
        item-title="tag"
        item-value="tag"
    >
        <template #chip="{ props, item }">
            <v-chip v-bind="props" variant="flat" :color="stc(item.raw.tag.replace(/ .*/, ''))" />
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

const pickedTags = defineModel<string[]>({ default: [] });

const search = ref<string>("");

const tagRepo = useRepo(TagRepository);
const tags = computed(() => useSortBy(tagRepo.orderBy("tag").get(), (tag) => !pickedTags.value.includes(tag.tag)));

watchEffect(async () => {
    await tagRepo.fetch(search.value);
});

async function onIntersect(isIntersecting: boolean) {
    if (isIntersecting) await tagRepo.fetch(search.value);
}
</script>
