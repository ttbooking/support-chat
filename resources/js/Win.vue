<template>
    <div>
        <VueWinBox
            v-for="[id, window] in windows"
            :key="id"
            v-model="window.value"
            :icon="icon"
            class="modern"
            minwidth="300"
            minheight="450"
            @close="closeWindow"
        >
            <SupportChat v-model="window.value.title" :height="window.value.height" />
        </VueWinBox>
    </div>
</template>

<script setup lang="ts">
import VueWinBox from "@/components/VueWinBox.vue";
import SupportChat from "@/components/SupportChat.vue";
import { useWindowManager } from "@/composables";

import icon from "../images/favicon.svg";

const { windows, createWindow, closeWindow } = useWindowManager();

window.chat.open = createWindow;
</script>
