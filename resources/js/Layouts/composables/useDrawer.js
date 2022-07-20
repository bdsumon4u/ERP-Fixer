import { ref } from "vue";

export function useDrawer() {
    const drawerOpen = ref(false);
    const activeTab = ref(null);

    const drawerToggle = () => {
        drawerOpen.value = !drawerOpen.value;
    };

    const isTabOpen = (tab, def = false) => {
        return activeTab.value ? activeTab.value === tab : def;
    };

    return {
        activeTab,
        drawerOpen,
        drawerToggle,
        isTabOpen,
    };
}
