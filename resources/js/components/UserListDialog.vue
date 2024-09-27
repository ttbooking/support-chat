<template>
    <v-dialog
        v-model="show"
        persistent
        width="auto"
        min-width="300"
        @keydown.enter="show = false"
        @keydown.esc="show = false"
    >
        <v-card :title="title">
            <v-card-text>
                <v-autocomplete
                    v-model="participants"
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
import { ref, computed } from "vue";
import { useRepo } from "pinia-orm";
import UserRepository from "@/repositories/UserRepository";

const show = defineModel<boolean>("show", { default: false });

defineProps<{ title: string }>();

const userRepo = computed(() => useRepo(UserRepository));

const users = computed(() => userRepo.value.orderBy("username").get());

const participants = ref<string[]>([]);

async function onIntersect(entries, observer, isIntersecting) {
    if (isIntersecting) await userRepo.value.fetch();
}
</script>
