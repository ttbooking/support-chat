<template>
    <v-confirm-edit v-model="text">
        <template #default="{ model, save, cancel, isPristine }">
            <v-dialog
                v-model="show"
                persistent
                width="auto"
                min-width="300"
                @keydown.enter="isPristine || close(save)"
                @keydown.esc="close(cancel)"
            >
                <v-card :title="title">
                    <v-card-text>
                        <v-text-field v-model="model.value" :placeholder="text" density="compact" autofocus />
                    </v-card-text>
                    <v-card-actions>
                        <v-btn :disabled="isPristine" @click="close(save)">OK</v-btn>
                        <v-btn @click="close(cancel)">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>
    </v-confirm-edit>
</template>

<script setup lang="ts">
const text = defineModel<string>({ required: true });
const show = defineModel<boolean>("show", { default: false });

defineProps<{ title: string }>();

const close = (action?: () => void) => {
    show.value = false;
    action?.();
};
</script>
