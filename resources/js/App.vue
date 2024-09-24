<template>
    <VueWinBox
        v-for="[id, window] in windows"
        :key="id"
        v-model="window.value"
        title="My Window"
        minwidth="300"
        minheight="450"
        class="modern"
        @close="closeWindow"
    >
        <SupportChat :height="window.value.height" />
    </VueWinBox>
    <v-fab icon="$vuetify" color="primary" position="fixed" location="bottom right" @click="createWindow" />
</template>

<script setup lang="ts">
import VueWinBox from "@/components/VueWinBox.vue";
import SupportChat from "@/components/SupportChat.vue";
import { useWindowManager } from "@/composables";

const { windows, createWindow, closeWindow } = useWindowManager();
</script>
