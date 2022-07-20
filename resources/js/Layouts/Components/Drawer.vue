<script setup>
import { TemplateIcon, XIcon } from "@heroicons/vue/outline";
import { useDrawer } from "../composables/useDrawer";

const props = defineProps({
    drawerOpen: Boolean,
    drawerToggle: Function,
});

const { activeTab, isTabOpen } = useDrawer();

const activeClass = (tab, def) => {
    return isTabOpen(tab, def) ? "border-blue-600" : "border-transparent";
};
</script>

<template>
    <aside
        :class="[drawerOpen ? 'right-0' : '-right-72']"
        class="w-72 fixed z-50 top-0 right-0 bottom-0 transition-all duration-300"
    >
        <header class="w-72 fixed z-30 bg-gray-200 dark:bg-black shadow">
            <div
                class="flex items-center justify-between h-14 px-2 border-b dark:border-gray-700 dark:text-gray-200"
            >
                <ul class="flex">
                    <li
                        :class="activeClass('tab-1', true)"
                        class="mb-0.5 border-b-2"
                    >
                        <button @click="activeTab = 'tab-1'" class="h-14 px-3">
                            <TemplateIcon class="w-5 h-5" />
                        </button>
                    </li>
                    <li :class="activeClass('tab-2')" class="mb-0.5 border-b-2">
                        <button @click="activeTab = 'tab-2'" class="h-14 px-3">
                            <TemplateIcon class="w-5 h-5" />
                        </button>
                    </li>
                    <li :class="activeClass('tab-3')" class="mb-0.5 border-b-2">
                        <button @click="activeTab = 'tab-3'" class="h-14 px-3">
                            <TemplateIcon class="w-5 h-5" />
                        </button>
                    </li>
                </ul>
                <button
                    @click.stop="drawerToggle"
                    type="button"
                    class="h-14 px-3"
                >
                    <XIcon class="w-5 h-5" />
                </button>
            </div>
        </header>
        <div
            class="w-72 fixed h-full top-14 z-30 bg-gray-100 dark:bg-black dark:text-gray-200 shadow"
        >
            <div
                v-if="isTabOpen('tab-1', true)"
                class="h-full scrollbar scrollbar-none"
            >
                <div class="p-2">Tab One</div>
            </div>
            <div
                v-if="isTabOpen('tab-2')"
                class="h-full scrollbar scrollbar-none"
            >
                <div class="p-2">Tab Two</div>
            </div>
            <div
                v-if="isTabOpen('tab-3')"
                class="h-full scrollbar scrollbar-none"
            >
                <div class="p-2">Tab Three</div>
            </div>
        </div>
    </aside>
</template>
