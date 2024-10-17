<template>
    <div>
        <VueWinBox
            v-for="[id, window] in windows"
            :key="id"
            v-model="window.value"
            class="modern"
            minwidth="300"
            minheight="450"
            @close="closeWindow"
        >
            <SupportChat v-model="window.value.title" :height="window.value.height" />
        </VueWinBox>
        <v-fab class="fixed" icon="$vuetify" color="primary" @click="createWindow" />
    </div>
</template>

<script setup lang="ts">
import VueWinBox from "@/components/VueWinBox.vue";
import SupportChat from "@/components/SupportChat.vue";
import { useWindowManager } from "@/composables";

const { windows, createWindow, closeWindow } = useWindowManager();
</script>

<style scoped>
.fixed {
    position: fixed;
    right: 64px;
    bottom: 64px;
    z-index: 5000;
}
</style>
