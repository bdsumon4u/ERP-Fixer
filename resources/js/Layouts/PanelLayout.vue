<script setup>
import JetBanner from "@/Jetstream/Banner.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import Drawer from "./Components/Drawer.vue";
import Footer from "./Components/Footer.vue";
import Header from "./Components/Header.vue";
import SearchBox from "./Components/SearchBox.vue";
import Sidebar from "./Components/Sidebar.vue";
import { useDrawer } from "./composables/useDrawer";
import { useScreen } from "./composables/useScreen";
import { useSidebar } from "./composables/useSidebar";

const props = defineProps({
    title: String,
});

const { screenType } = useScreen();
const { sidebarFull, sidebarToggle } = useSidebar();
const { drawerOpen, drawerToggle } = useDrawer();

const searchBoxOpen = ref(false);

const openSearchBox = () => (searchBoxOpen.value = true);
const closeSearchBox = () => (searchBoxOpen.value = false);
</script>

<template>
    <div
        :class="{ 'sidebar-maximized': sidebarFull }"
        v-bind:screen="screenType"
    >
        <Head :title="title" />

        <jet-banner />

        <div
            v-show="sidebarFull"
            @click="sidebarToggle"
            class="fixed inset-0 z-10 w-screen h-screen bg-black bg-opacity-25 sm:hidden"
        ></div>
        <div
            v-show="drawerOpen"
            @click="drawerToggle"
            class="fixed inset-0 z-50 w-screen h-screen bg-black bg-opacity-25 sm:hidden"
        ></div>
        <div
            class="min-h-screen flex flex-col flex-auto flex-shrink-0 antialiased bg-white dark:bg-gray-700 text-black"
        >
            <slot
                name="header"
                :sidebarToggle="sidebarToggle"
                :openSearchBox="openSearchBox"
                :drawerToggle="drawerToggle"
            >
                <Header
                    :sidebarToggle="sidebarToggle"
                    :openSearchBox="openSearchBox"
                    :drawerToggle="drawerToggle"
                />
            </slot>
            <slot
                name="sidebar"
                :sidebarFull="sidebarFull"
                :sidebarToggle="sidebarToggle"
            >
                <Sidebar
                    :sidebarFull="sidebarFull"
                    :sidebarToggle="sidebarToggle"
                />
            </slot>
            <slot name="drawer">
                <Drawer :drawerOpen="drawerOpen" :drawerToggle="drawerToggle" />
            </slot>

            <main
                :class="{ 'lg:ml-60': sidebarFull }"
                class="flex flex-col flex-1 mt-14 mb-14 sm:mb-10 ml-0 sm:ml-10"
            >
                <slot></slot>
            </main>

            <Footer
                :sidebarToggle="sidebarToggle"
                :openSearchBox="openSearchBox"
                :drawerToggle="drawerToggle"
            />
        </div>
        <SearchBox
            id="search-modal"
            searchId="search"
            :searchBoxOpen="searchBoxOpen"
            :openSearchBox="openSearchBox"
            :closeSearchBox="closeSearchBox"
        />
    </div>
</template>

<style lang="scss">
@import "../../css/panel.scss";
</style>
