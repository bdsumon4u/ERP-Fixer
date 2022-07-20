<script setup>
import {
    ChartBarIcon,
    MenuIcon,
    SearchIcon,
    ViewGridIcon,
} from "@heroicons/vue/outline";

const props = defineProps({
    sidebarToggle: Function,
    openSearchBox: Function,
    drawerToggle: Function,
});
</script>

<template>
    <div
        class="h-14 bg-white dark:bg-black fixed z-30 shadow bottom-0 w-full border-t dark:border-t-gray-500 sm:hidden"
    >
        <ul class="flex justify-around">
            <li class="p-2">
                <a @click.prevent="sidebarToggle" href="#" class="link-item">
                    <span class="block h-6">
                        <MenuIcon class="link-icon" />
                    </span>
                    <span class="link-text">Menu</span>
                </a>
            </li>
            <li class="p-2">
                <Link href="#" class="link-item">
                    <span class="block h-6">
                        <ChartBarIcon class="link-icon" />
                    </span>
                    <span class="link-text">Analytics</span>
                </Link>
            </li>
            <li class="p-2">
                <a
                    @click.stop="openSearchBox"
                    aria-controls="search-modal"
                    href="#"
                    class="link-item"
                >
                    <span class="block h-6">
                        <SearchIcon class="link-icon" />
                    </span>
                    <span class="link-text">Search</span>
                </a>
            </li>
            <li class="p-2">
                <a @click.prevent="drawerToggle" href="#" class="link-item">
                    <span class="block h-6">
                        <ViewGridIcon class="link-icon" />
                    </span>
                    <span class="link-text">Utilities</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<style lang="scss" scoped>
.link-item {
    @apply flex flex-col items-center justify-center text-gray-500 dark:text-gray-200 hover:text-indigo-500;
}
.link-icon {
    @apply w-5 h-5;
}
.link-text {
    @apply block text-xs leading-none mt-1;
}
</style>
