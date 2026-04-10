import { shallowRef, type ShallowRef } from "vue";
import { useStorage, type StorageLike } from "@vueuse/core";
import { nanoid } from "nanoid";
import type { WinBoxModel, WinBoxProps } from "@/types/winbox";

export function useWindowManager(windowDefaults: Partial<WinBoxProps & WinBoxModel> = {}, storage?: StorageLike) {
    const defaults: Omit<WinBoxProps & WinBoxModel, "id"> = {
        class: "modern",
        minwidth: 250,
        minheight: 400,
        overflow: true,
        title: "My Window",
        index: 1000,
        x: 100,
        y: 100,
        width: 500,
        height: 400,
        min: false,
        max: false,
        full: false,
        hidden: false,
        focused: true,
        ...windowDefaults,
    };

    const windows = useStorage<Map<string, ShallowRef<WinBoxModel>>>("support-chat", new Map(), storage, {
        listenToStorageChanges: true,
        serializer: {
            read: (raw) =>
                new Map(
                    (JSON.parse(raw) as WinBoxModel[]).map((window) => [
                        window.id,
                        shallowRef({ ...window, index: defaults.index }),
                    ]),
                ),
            write: (map) =>
                JSON.stringify(
                    [...map.values()]
                        .sort((a, b) => a.value.index - b.value.index)
                        .map((window) => {
                            const { index, ...rest } = window.value;
                            return rest;
                        }),
                ),
        },
    });

    function createWindow() {
        const id = "winbox-" + nanoid(7);

        windows.value.set(id, shallowRef({ id, ...defaults }));
    }

    function closeWindow(id: string) {
        windows.value.delete(id);
    }

    return { defaults, windows, createWindow, closeWindow };
}
