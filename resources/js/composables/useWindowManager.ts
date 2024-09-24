import { shallowRef, type ShallowRef } from "vue";
import { useStorage, type StorageLike } from "@vueuse/core";
import { nanoid } from "nanoid";
import type { WinBoxModel } from "@/types/winbox";

export function useWindowManager(storage?: StorageLike) {
    const windows = useStorage<Map<string, ShallowRef<WinBoxModel>>>("support-chat", new Map(), storage, {
        listenToStorageChanges: true,
        serializer: {
            read: (raw) => {
                const object: Record<string, WinBoxModel> = JSON.parse(raw);
                const map = new Map();
                for (const [id, window] of Object.entries(object)) {
                    window.id = id;
                    map.set(id, shallowRef(window));
                }
                return map;
            },
            write: (value) => {
                const object: Record<string, Omit<WinBoxModel, "id">> = {};
                for (const [id, window] of value) {
                    const { id: _, ...rest } = window.value;
                    object[id] = rest;
                }
                return JSON.stringify(object);
            },
        },
    });

    function createWindow() {
        const id = "winbox-" + nanoid(7);

        windows.value.set(
            id,
            shallowRef({
                id,
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
