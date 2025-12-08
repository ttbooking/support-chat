<template>
    <v-autocomplete
        v-model="participants"
        v-model:search="search"
        multiple
        chips
        closable-chips
        :items="users"
        item-title="username"
        return-object
    >
        <template #chip="{ props, item }">
            <v-chip v-bind="props" :prepend-avatar="item.raw.avatar" />
        </template>
        <template #item="{ props, item }">
            <v-list-item v-bind="props" :prepend-avatar="item.raw.avatar" :subtitle="item.raw.credential" />
        </template>
        <template #append-item>
            <v-list-item v-intersect="onIntersect">Loading...</v-list-item>
        </template>
    </v-autocomplete>
</template>

<script setup lang="ts">
import { ref, computed, watchEffect } from "vue";
import { useRepo } from "pinia-orm";
import { useSortBy } from "pinia-orm/helpers";
import UserRepository from "@/repositories/UserRepository";
import type { RoomUser } from "vue-advanced-chat";

const participants = defineModel<RoomUser[]>({ default: [] });

const search = ref<string>("");

const userRepo = useRepo(UserRepository);
const users = computed(() =>
    useSortBy(
        userRepo.orderBy("username").get(),
        (user) => !participants.value.map((user) => user._id).includes(user._id),
    ),
);

watchEffect(async () => {
    await userRepo.fetch(search.value);
});

async function onIntersect(isIntersecting: boolean) {
    if (isIntersecting) await userRepo.fetch(search.value);
}
</script>
