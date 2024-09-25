<template>
    <Teleport v-if="winbox" :to="`#${winbox.id} .wb-body`"><slot /></Teleport>
</template>

<script setup lang="ts">
import { ref, watch, onBeforeMount, onScopeDispose, toRaw } from "vue";
import "winbox";
import type { WinBoxModel } from "@/types/winbox";

const winbox = ref<WinBox | null>(null);

const props = withDefaults(
    defineProps<{
        title?: string;
        class?: string | string[];
        minheight?: string | number;
        minwidth?: string | number;
        overflow?: boolean;
    }>(),
    {
        title: "My Window",
        class: "modern",
        minwidth: 250,
        minheight: 400,
        overflow: true,
    },
);

const model = defineModel<WinBoxModel>({
    required: true,
    default: {
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

watch(
    () => props.title,
    (title) => winbox.value?.setTitle(title),
);

watch(model, (window) => {
    if (winbox.value?.x !== window.x || winbox.value?.y !== window.y) {
        winbox.value?.move(window.x, window.y);
    }
    if (winbox.value?.width !== window.width || winbox.value?.height !== window.height) {
        winbox.value?.resize(window.width, window.height);
    }
    if (winbox.value?.min !== window.min) {
        winbox.value?.minimize(window.min);
    }
    if (winbox.value?.max !== window.max) {
        winbox.value?.maximize(window.max);
    }
    if (winbox.value?.full !== window.full) {
        winbox.value?.fullscreen(window.full);
    }
    if (winbox.value?.hidden !== window.hidden) {
        winbox.value?.hide(window.hidden);
    }
    if (winbox.value?.focused !== window.focused) {
        winbox.value?.focus(window.focused);
    }
});

onBeforeMount(() => {
    winbox.value = new WinBox(props.title, {
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
