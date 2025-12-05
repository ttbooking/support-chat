<template>
    <v-confirm-edit v-model="room" @save="show = false" @cancel="show = false">
        <template #default="{ model, actions, save, cancel, isPristine }">
            <v-dialog
                v-model="show"
                persistent
                width="auto"
                min-width="600"
                @keydown.enter="save"
                @keydown.esc="cancel"
            >
                <v-card :title="$t('room_configuration', { name: room.roomName })">
                    <v-card-text>
                        <v-row no-gutters>
                            <v-col cols="6">
                                <v-text-field
                                    v-model="model.value.roomId"
                                    :label="$t('room_id')"
                                    variant="plain"
                                    readonly
                                    tabindex="-1"
                                />
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    v-model="model.value.creator.username"
                                    :label="$t('creator')"
                                    variant="plain"
                                    readonly
                                    tabindex="-1"
                                >
                                    <template #prepend-inner>
                                        <v-avatar size="24" :image="model.value.creator.avatar" />
                                    </template>
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col>
                                <v-text-field
                                    v-model="model.value.roomName"
                                    :placeholder="room.roomName"
                                    :label="$t('room_name')"
                                    variant="outlined"
                                    density="compact"
                                />
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col>
                                <TagAutocomplete
                                    v-model="model.value.tags"
                                    :label="$t('tags')"
                                    variant="outlined"
                                    density="compact"
                                />
                            </v-col>
                        </v-row>
                        <v-row no-gutters>
                            <v-col>
                                <UserAutocomplete
                                    v-model="model.value.users"
                                    :label="$t('participants')"
                                    variant="outlined"
                                    density="compact"
                                />
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn :disabled="isPristine" @click="save">{{ $t("ok") }}</v-btn>
                        <v-btn @click="cancel">{{ $t("cancel") }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>
    </v-confirm-edit>
</template>

<script setup lang="ts">
import UserAutocomplete from "@/components/UserAutocomplete.vue";
import TagAutocomplete from "@/components/TagAutocomplete.vue";
import type { Room as BaseRoom } from "@/types";

const room = defineModel<BaseRoom>({ required: true });
const show = defineModel<boolean>("show", { default: false });
</script>
