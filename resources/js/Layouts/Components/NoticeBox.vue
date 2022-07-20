<script setup>
import { BellIcon } from "@heroicons/vue/outline";
import { ref } from "vue";
import { useEscOrOutside } from "../../composables/useEscOrOutside";

const open = ref(false);
const container = ref(null);

const notifications = ref({
    unread: 2,
    latest: [
        {
            id: 1,
            message: "The fifth notification.",
            created_at: "20-Jul-2022 04:30 PM",
            read_at: "20-Jul-2022 04:30 PM",
        },
        {
            id: 2,
            message: "The fourth notification.",
            created_at: "20-Jul-2022 04:30 PM",
        },
        {
            id: 3,
            message: "The third notification.",
            created_at: "20-Jul-2022 04:30 PM",
            read_at: "20-Jul-2022 04:30 PM",
        },
        {
            id: 4,
            message: "The second notification.",
            created_at: "20-Jul-2022 04:30 PM",
        },
        {
            id: 5,
            message: "The very first notification.",
            created_at: "20-Jul-2022 04:30 PM",
            read_at: "20-Jul-2022 04:30 PM",
        },
    ],
});

useEscOrOutside(container, () => (open.value = false));
</script>

<template>
    <div ref="container">
        <button
            @click="open = !open"
            class="relative h-10 w-10 text-blue-400 dark:text-gray-200 rounded-md bg-gray-100 dark:bg-gray-500 hover:text-blue-600 hover:bg-blue-100 grid place-content-center focus:outline-none focus:ring"
        >
            <BellIcon class="w-5 h-5" />
            <span
                class="absolute -top-1 -right-2 w-5 h-5 text-sm text-white rounded-md bg-red-500"
                >{{ notifications.unread }}</span
            >
        </button>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="absolute z-50 mt-2 rounded-md shadow-lg w-72 -mr-16 md:mr-0 origin-top-right right-0"
                style="display: none"
            >
                <div
                    class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-black dark:text-gray-200"
                >
                    <div class="z-10 right-0">
                        <div class="text-sm font-bold uppercase py-2 px-4">
                            Notifications
                        </div>
                        <ul class="px-2 py-1">
                            <li
                                v-for="notification in notifications.latest"
                                class="hover:bg-gray-200 my-1 rounded-sm"
                                :class="[
                                    notification.read_at
                                        ? 'bg-gray-100'
                                        : 'bg-yellow-100',
                                ]"
                                :key="notification.id"
                            >
                                <a href="" class="block px-2 py-2 nc">
                                    <span class="block text-sm mb-2"
                                        >📣
                                        <span class="rx text-gray-800">{{
                                            notification.message
                                        }}</span></span
                                    >
                                    <span
                                        class="block text-black text-xs font-weight-500 opacity-75"
                                        >{{ notification.created_at }}</span
                                    >
                                </a>
                            </li>
                            <li
                                v-if="notifications.latest.length > 3"
                                class="flex items-center justify-center"
                            >
                                <small class="text-xs -mt-1">.......</small>
                            </li>
                            <li class="flex space-x-1">
                                <strong
                                    v-if="!notifications.latest.length"
                                    class="border dark:border-gray-500 p-2 text-red-500 w-full"
                                    >Nothing Found.</strong
                                >
                                <Link
                                    v-if="notifications.unread > 0"
                                    class="p-2 bg-gray-200 hover:bg-gray-300 dark:text-gray-800 w-full text-sm grid place-content-center font-bold rounded-sm"
                                    >Mark All As Read
                                </Link>
                                <Link
                                    v-if="notifications.latest.length > 3"
                                    class="p-2 bg-gray-200 hover:bg-gray-300 dark:text-gray-800 w-full text-sm grid place-content-center font-bold rounded-sm"
                                    >View All
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
