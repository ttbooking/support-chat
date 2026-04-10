<template>
    <div>
        <VueWinBox
            v-for="[id, window] in windows"
            :key="id"
            v-model="window.value"
            :icon="icon"
            :class="defaults.class"
            :minwidth="defaults.minwidth"
            :minheight="defaults.minheight"
            :overflow="defaults.overflow"
            @close="closeWindow"
        >
            <AdvancedChat
                v-model="window.value.title"
                :height="window.value.height"
                :index="defaults.index + window.value.index + 1000"
            />
        </VueWinBox>
    </div>
</template>

<script setup lang="ts">
import VueWinBox from "@/components/VueWinBox.vue";
import AdvancedChat from "@/components/AdvancedChat.vue";
import { useWindowManager } from "@/composables";

import icon from "../images/favicon.svg";

const { defaults, windows, createWindow, closeWindow } = useWindowManager(window.chat.windowDefaults);

window.chat.open = createWindow;
</script>
