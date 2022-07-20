import { useEventListener } from "@vueuse/core";
import { onMounted, ref } from "vue";

export function useScreen() {
    const windowWidth = ref(window.innerWidth);
    const screenType = ref(null);

    const breakpoints = {
        "2xl": 1536,
        xl: 1280,
        lg: 1024,
        md: 768,
        sm: 640,
        xs: 0,
    };

    const onResize = () => {
        windowWidth.value = window.innerWidth;
        detectScreenType();
    };

    const isDevice = (size) => {
        return windowWidth.value >= breakpoints[size];
    };

    const detectScreenType = () => {
        for (const device in breakpoints) {
            if (isDevice(device)) {
                return (screenType.value = device);
            }
        }
    };

    onMounted(onResize);
    useEventListener(window, "resize", onResize);

    return {
        breakpoints,
        isDevice,
        screenType,
        windowWidth,
    };
}
