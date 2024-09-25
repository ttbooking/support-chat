import { shallowRef, type ShallowRef } from "vue";
import { useStorage, type StorageLike } from "@vueuse/core";
import { nanoid } from "nanoid";
import type { WinBoxModel } from "@/types/winbox";

export function useWindowManager(storage?: StorageLike) {
    const windows = useStorage<Map<string, ShallowRef<WinBoxModel>>>("support-chat", new Map(), storage, {
        listenToStorageChanges: true,
        serializer: {
            read: (raw) =>
                new Map(
                    (JSON.parse(raw) as WinBoxModel[]).map((window) => [
                        window.id,
                        shallowRef({ ...window, index: 1000 }),
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

        windows.value.set(
            id,
            shallowRef({
                id,
                index: 1000,
                x: 100,
                y: 100,
                width: 500,
                height: 300,
                min: false,
                max: false,
                full: false,
                hidden: false,
                focused: true,
            }),
        );
    }

    function closeWindow(id: string) {
        windows.value.delete(id);
    }

    return { windows, createWindow, closeWindow };
}
