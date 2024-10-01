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
            <v-chip v-bind="props" :prepend-avatar="item.raw.avatar" :text="item.raw.username" />
        </template>
        <template #item="{ props, item }">
            <v-list-item
                v-bind="props"
                :prepend-avatar="item.raw.avatar"
                :title="item.raw.username"
                :subtitle="item.raw.email"
            />
        </template>
        <template #append-item>
            <v-list-item v-intersect="onIntersect">Loading...</v-list-item>
        </template>
    </v-autocomplete>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { useRepo } from "pinia-orm";
import UserRepository from "@/repositories/UserRepository";
import type { RoomUser } from "vue-advanced-chat";

const participants = defineModel<RoomUser[]>({ required: true });

const search = ref<string>("");

const userRepo = computed(() => useRepo(UserRepository));
const users = computed(() =>
    userRepo.value
        // TODO: custom filtering, use with "no-filter" property
        /*.where(
            (user) =>
                participants.value.map((user) => user._id).includes(user._id) ||
                user.username.toLowerCase().startsWith(search.value.toLowerCase()),
        )*/
        .orderBy("username")
        .get(),
);

watch(search, async (search) => {
    await userRepo.value.fetch(search);
});

async function onIntersect(isIntersecting: boolean) {
    if (isIntersecting) await userRepo.value.fetch(search.value);
}
</script>
