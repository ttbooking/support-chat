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
                        <v-text-field
                            v-model="model.value.roomName"
                            :placeholder="room.roomName"
                            :label="$t('room_name')"
                            variant="outlined"
                            density="compact"
                        />
                        <UserAutocomplete
                            v-model="model.value.users"
                            :label="$t('participants')"
                            variant="outlined"
                            density="compact"
                        />
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
import type { Room as BaseRoom } from "vue-advanced-chat";

const room = defineModel<BaseRoom>({ required: true });
const show = defineModel<boolean>("show", { default: false });
</script>
