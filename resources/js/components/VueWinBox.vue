<template>
    <Teleport v-if="winbox" :to="`#${winbox.id} .wb-body`"><slot /></Teleport>
</template>

<script setup lang="ts">
import { ref, watchEffect, onBeforeMount, onScopeDispose, toRaw } from "vue";
import "winbox";
import type { WinBoxModel } from "@/types/winbox";

const winbox = ref<WinBox | null>(null);

const props = withDefaults(
    defineProps<{
        icon?: string;
        class?: string | string[];
        minheight?: string | number;
        minwidth?: string | number;
        overflow?: boolean;
    }>(),
    {
        icon: undefined,
        class: "modern",
        minwidth: 250,
        minheight: 400,
        overflow: true,
    },
);

const model = defineModel<WinBoxModel>({
    required: true,
    default: {
        title: "My Window",
        index: 1000,
        x: 100,
        y: 100,
        width: 500,
        height: 400,
        focused: true,
    },
});

const emit = defineEmits<{
    (e: "close", id: string): void;
}>();

watchEffect(() => {
    if (winbox.value?.title !== model.value.title) {
        winbox.value?.setTitle(model.value.title);
    }
    if (winbox.value?.x !== model.value.x || winbox.value?.y !== model.value.y) {
        winbox.value?.move(model.value.x, model.value.y);
    }
    if (winbox.value?.width !== model.value.width || winbox.value?.height !== model.value.height) {
        winbox.value?.resize(model.value.width, model.value.height);
    }
    if (winbox.value?.min !== model.value.min) {
        winbox.value?.minimize(model.value.min);
    }
    if (winbox.value?.max !== model.value.max) {
        winbox.value?.maximize(model.value.max);
    }
    if (winbox.value?.full !== model.value.full) {
        winbox.value?.fullscreen(model.value.full);
    }
    if (winbox.value?.hidden !== model.value.hidden) {
        winbox.value?.hide(model.value.hidden);
    }
    if (winbox.value?.focused !== model.value.focused) {
        winbox.value?.focus(model.value.focused);
    }
});

onBeforeMount(() => {
    winbox.value = new WinBox({
        ...props,
        ...model.value,
        oncreate() {
            model.value.id = this.id;
        },
        onmove(x, y) {
            model.value.x = x;
            model.value.y = y;
        },
        onresize(width, height) {
            model.value.width = width;
            model.value.height = height;
        },
        onclose() {
            emit("close", this.id);
            winbox.value = null;
            return false;
        },
        onminimize() {
            model.value.min = true;
        },
        onmaximize() {
            model.value.max = true;
        },
        onfullscreen() {
            model.value.full = true;
        },
        onrestore() {
            model.value.min = false;
            model.value.max = false;
            model.value.full = false;
        },
        onhide() {
            model.value.hidden = true;
        },
        onshow() {
            model.value.hidden = false;
        },
        onfocus() {
            model.value.focused = true;
            model.value.index = this.index;
        },
        onblur() {
            model.value.focused = false;
            model.value.index = this.index;
        },
    });

    //model.value.id = winbox.value.id;
});

/*onMounted(() => {
    model.value.id = winbox.value.id;
});*/

onScopeDispose(() => {
    toRaw(winbox.value)?.close();
});
</script>
