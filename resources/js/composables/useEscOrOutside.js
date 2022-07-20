import { onClickOutside, onKeyUp } from "@vueuse/core";

export function useEscOrOutside(rootEl, callback) {
    onClickOutside(rootEl, callback, true);
    onKeyUp("Escape", callback);
}
