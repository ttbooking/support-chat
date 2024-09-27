<template>
    <v-dialog
        v-model="show"
        persistent
        width="auto"
        min-width="600"
        @keydown.enter="show = false"
        @keydown.esc="show = false"
    >
        <v-card :title="title">
            <v-card-text>
                <v-autocomplete
                    v-model="participants"
                    v-model:search="search"
                    no-filter
                    multiple
                    chips
                    closable-chips
                    density="compact"
                    :items="users"
                    item-title="username"
                    item-value="_id"
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
            </v-card-text>
            <v-card-actions>
                <v-btn @click="show = false">{{ $t("ok") }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { useRepo } from "pinia-orm";
import UserRepository from "@/repositories/UserRepository";

const show = defineModel<boolean>("show", { default: false });

defineProps<{ title: string }>();

const participants = ref<string[]>([]);
const search = ref<string>("");

const userRepo = computed(() => useRepo(UserRepository));
const users = computed(() =>
    userRepo.value
        // TODO: custom filtering, use with "no-filter" property
        .where(
            (user) =>
                participants.value.includes(user._id) ||
                user.username.toLowerCase().startsWith(search.value.toLowerCase()),
        )
        .orderBy("username")
        .get(),
);

watch(search, async (search) => {
    await userRepo.value.fetch(search);
});

async function onIntersect(entries, observer, isIntersecting) {
    if (isIntersecting) await userRepo.value.fetch(search.value);
}
</script>
