import { useEventListener } from "@vueuse/core";
import { onMounted, ref } from "vue";
import { useScreen } from "./useScreen";

export function useSidebar(items = []) {
    const open_id = ref(null);
    const sidebarFull = ref(false);
    const { isDevice } = useScreen();

    const sidebarToggle = () => {
        sidebarFull.value = !sidebarFull.value;
    };

    const shouldMaximize = () => {
        return (sidebarFull.value = isDevice("lg"));
    };

    const setSidebarHeight = () => {
        const target = document.querySelector(".sidebar > div > ul");
        const px = document.body.scrollHeight - 3.5 * 16;
        target.style.height = px / 16 + "rem";
    };

    const doOpen = (item_id) => {
        open_id.value = open_id.value === item_id ? null : item_id;
    };

    const hasSub = (item) => {
        let _class = "";
        if (item.children) {
            _class += "has-sub";
            if (open_id.value === item.id) {
                _class += " open";
            }
        }
        return _class;
    };

    const isActive = (item) => {
        if (item.active?.length) {
            return routeIn(item.active ?? []);
        }

        let href = item.href;
        if (href && !href.startsWith(location.origin)) {
            href = location.origin + href;
        }
        return location.href === href;
    };

    const routeIn = (active) => {
        return active.some((item) => route().current(item));
    };

    onMounted(() => {
        shouldMaximize(); // onCreated
        setSidebarHeight(); // onMounted
    });

    useEventListener(window, "resize", () => {
        shouldMaximize();
        setSidebarHeight();
    });

    return {
        doOpen,
        hasSub,
        isActive,
        items,
        open_id,
        sidebarFull,
        sidebarToggle,
    };
}
