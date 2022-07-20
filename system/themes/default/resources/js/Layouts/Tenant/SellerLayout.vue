<script setup>
import Header from "@/Layouts/Components/Header.vue";
import Sidebar from "@/Layouts/Components/Sidebar.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
import { HomeIcon } from "@heroicons/vue/outline";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    title: String,
});

const items = [
    {
        id: "dashboard",
        name: "Dashboard",
        icon: HomeIcon,
        href: route("tenant.seller.dashboard"),
    },
];
</script>

<template>
    <PanelLayout :title="title">
        <template #header="{ sidebarToggle, openSearchBox, drawerToggle }">
            <Header
                :sidebarToggle="sidebarToggle"
                :openSearchBox="openSearchBox"
                :drawerToggle="drawerToggle"
            >
                <template #branding>
                    <img
                        @click.prevent="
                            Inertia.visit(route('tenant.seller.dashboard'))
                        "
                        class="w-40 sm:w-56 lg:w-44 h-12 cursor-pointer"
                        src="/storage/logo.png"
                        alt="Branding"
                    />
                </template>
                <template #header> </template>
            </Header>
        </template>
        <template #sidebar="{ sidebarFull, sidebarToggle }">
            <Sidebar
                :items="items"
                :key="Math.random()"
                :sidebarFull="sidebarFull"
                :sidebarToggle="sidebarToggle"
            />
        </template>
        <slot></slot>
    </PanelLayout>
</template>
