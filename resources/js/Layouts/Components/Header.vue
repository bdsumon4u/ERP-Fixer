<script setup>
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import {
    ChartBarIcon,
    MenuIcon,
    SearchIcon,
    ViewGridIcon,
} from "@heroicons/vue/outline";
import { Inertia } from "@inertiajs/inertia";
import NoticeBox from "./NoticeBox.vue";

const props = defineProps({
    sidebarToggle: Function,
    openSearchBox: Function,
    drawerToggle: Function,
});
</script>

<template>
    <header class="fixed w-full z-30 bg-white dark:bg-black shadow">
        <div
            class="flex items-center justify-between h-14 p-2 border-b dark:border-b-gray-500"
        >
            <div class="flex items-center justify-center">
                <slot name="branding">
                    <img
                        class="w-40 sm:w-56 lg:w-44 h-12"
                        src="/storage/logo.png"
                        alt="Branding"
                    />
                </slot>
                <!-- Mobile menu button -->
                <button
                    @click="sidebarToggle"
                    class="ml-3 w-8 h-8 text-blue-400 dark:text-gray-200 rounded-md bg-gray-100 dark:bg-gray-500 hover:text-blue-600 hover:bg-blue-100 hidden lg:grid place-content-center focus:outline-none focus:ring"
                >
                    <span aria-hidden="true">
                        <MenuIcon class="w-5 h-5" />
                    </span>
                </button>
            </div>

            <div class="hidden sm:flex mr-auto">
                <slot name="shortcuts">
                    <div class="ml-3 relative">
                        <button
                            class="w-8 h-8 text-blue-400 dark:text-gray-200 rounded-md bg-gray-100 dark:bg-gray-500 hover:text-blue-600 hover:bg-blue-100 hidden lg:grid place-content-center focus:outline-none focus:ring"
                        >
                            <span aria-hidden="true">
                                <ChartBarIcon class="w-5 h-5" />
                            </span>
                        </button>
                    </div>
                    <div class="ml-3 relative">
                        <button
                            class="w-8 h-8 text-blue-400 dark:text-gray-200 rounded-md bg-gray-100 dark:bg-gray-500 hover:text-blue-600 hover:bg-blue-100 hidden lg:grid place-content-center focus:outline-none focus:ring"
                            @click.stop="openSearchBox"
                            aria-controls="search-modal"
                        >
                            <span aria-hidden="true">
                                <SearchIcon class="w-5 h-5" />
                            </span>
                        </button>
                    </div>
                </slot>
            </div>

            <!-- Right Buttons -->
            <nav aria-label="Right Buttons" class="mr-3 flex items-center">
                <slot name="actions">
                    <NoticeBox class="ml-3 relative" />
                </slot>

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <jet-dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                v-if="
                                    $page.props.jetstream.managesProfilePhotos
                                "
                                class="flex text-sm border-2 border-transparent rounded-md focus:outline-none focus:border-gray-300 transition"
                            >
                                <img
                                    class="h-10 w-10 rounded-md object-cover"
                                    :src="$page.props.user.profile_photo_url"
                                    :alt="$page.props.user.name"
                                />
                            </button>

                            <span v-else class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white dark:bg-black hover:text-gray-700 focus:outline-none transition"
                                >
                                    {{ $page.props.user.name }}

                                    <svg
                                        class="ml-2 -mr-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Account
                            </div>

                            <jet-dropdown-link :href="route('profile.show')">
                                Profile
                            </jet-dropdown-link>

                            <jet-dropdown-link
                                :href="route('api-tokens.index')"
                                v-if="$page.props.jetstream.hasApiFeatures"
                            >
                                API Tokens
                            </jet-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form
                                @submit.prevent="Inertia.post(route('logout'))"
                            >
                                <jet-dropdown-link as="button">
                                    Log Out
                                </jet-dropdown-link>
                            </form>
                        </template>
                    </jet-dropdown>
                </div>
                <div class="ml-3 relative hidden sm:flex">
                    <button
                        @click="drawerToggle"
                        class="p-2 text-blue-400 dark:text-gray-200 rounded-md bg-gray-100 dark:bg-gray-500 hover:text-blue-600 hover:bg-blue-100 grid place-content-center focus:outline-none focus:ring"
                    >
                        <span aria-hidden="true">
                            <ViewGridIcon class="w-5 h-5" />
                        </span>
                    </button>
                </div>
            </nav>
        </div>
    </header>
</template>
