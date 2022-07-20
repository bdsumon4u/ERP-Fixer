<script setup>
import {
    ChatAltIcon as ChatIcon,
    CogIcon,
    LockClosedIcon as LockIcon,
    MoonIcon,
    SunIcon,
    TemplateIcon,
} from "@heroicons/vue/outline";
import { Link } from "@inertiajs/inertia-vue3";
import { computed } from "@vue/runtime-core";
import { UseDark } from "@vueuse/components";
import { onClickOutside } from "@vueuse/core";
import { useSidebar } from "../composables/useSidebar";

const props = defineProps({
    items: {
        type: Array,
        default: [],
    },
    sidebarFull: Boolean,
    sidebarToggle: Function,
});

const { items, hasSub, open_id, doOpen, isActive } = useSidebar(props.items);

const closeSidebarDropdown = () => {
    if (!props.sidebarFull) {
        open_id.value = null;
    }
};

const groupLink = (item) => {
    if (isActive(item)) {
        return "active bg-blue-800 dark:bg-blue-800 text-white-800";
    }
};

const itemTextGroup = computed(() => {
    return props.sidebarFull ? "inline-flex group-hover:inline-flex" : "hidden";
});

const itemText = computed(() => {
    if (props.sidebarFull) {
        return "group-hover:translate-x-2 transform-gpu transition-transform duration-200";
    }
});
</script>

<template>
    <div
        :class="[sidebarFull ? 'fixed h-full' : 'absolute h-auto']"
        class="w-60 sm:w-10 flex flex-col top-14 bottom-16 sm:bottom-0 -left-60 sm:left-0 text-white border-none z-10 sidebar"
    >
        <div
            :class="[sidebarFull ? 'scrollbar scrollbar-none' : '']"
            class="flex flex-col justify-between flex-grow bg-gray-900"
        >
            <ul
                :class="[sidebarFull ? 'pb-32' : 'pb-4']"
                class="flex flex-col pt-4 space-y-1"
            >
                <li
                    v-for="item in items"
                    class="relative"
                    :class="hasSub(item)"
                    :key="item.id"
                >
                    <div class="group">
                        <component
                            :is="item.children ? 'a' : Link"
                            :href="item.href ?? '#'"
                            @click.prevent="doOpen(item.id)"
                            :class="groupLink(item)"
                            class="item-link"
                        >
                            <span
                                :class="{ 'border-blue-400': isActive(item) }"
                                class="w-10 h-full flex justify-center items-center border-l-2 border-transparent"
                            >
                                <component
                                    class="w-5 h-5"
                                    :is="item.icon ? item.icon : TemplateIcon"
                                />
                            </span>
                            <span
                                :class="itemTextGroup"
                                class="pl-1 pr-2 h-full items-center grow text-sm tracking-wide overflow-hidden"
                            >
                                <span
                                    :class="itemText"
                                    class="font-bold overflow-hidden text-ellipsis"
                                >
                                    {{ item.name }}
                                </span>
                            </span>
                            <span
                                v-if="item.badge"
                                v-show="sidebarFull"
                                class="inline-flex px-1.5 py-0.5 ml-auto mr-2 text-xs font-medium tracking-wide text-blue-500 bg-indigo-50 rounded-md"
                            >
                                {{ item.badge }}
                            </span>
                            <span
                                v-if="item.children"
                                v-show="sidebarFull"
                                class="inline-flex justify-center items-center w-6 h-6 mr-2 flex-none text-gray-300"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    width="16"
                                    height="16"
                                    class="inline-block"
                                >
                                    <path
                                        icon="+"
                                        fill="currentColor"
                                        d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"
                                    ></path>
                                    <path
                                        icon="-"
                                        fill="currentColor"
                                        d="M19,13H5V11H19V13Z"
                                    ></path>
                                </svg>
                            </span>
                        </component>
                    </div>
                    <ul
                        :ref="(el) => onClickOutside(el, closeSidebarDropdown)"
                        v-show="item.children && open_id === item.id"
                        class="bg-gray-800 dark:bg-black"
                    >
                        <li class="title item-link">
                            <span
                                class="font-bold ml-6 grow text-sm tracking-wide truncate"
                            >
                                {{ item.name }}
                            </span>
                        </li>
                        <li v-for="child in item.children" :key="child.id">
                            <Link
                                :href="child.href"
                                :class="{ 'text-blue-400': isActive(child) }"
                                class="item-link border-l-2 border-transparent"
                            >
                                <span class="ml-6 item-text">
                                    {{ child.name }}
                                </span>
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>

            <div
                :class="[sidebarFull ? 'h-12' : 'sm:w-10']"
                class="w-60 flex fixed bottom-14 sm:bottom-0 border-t border-gray-700"
            >
                <ul
                    :class="[sidebarFull ? 'px-4' : 'flex-col-reverse']"
                    class="w-full flex items-center justify-between bg-gray-800"
                >
                    <slot name="shortcuts">
                        <li class="cursor-pointer text-white">
                            <UseDark v-slot="{ isDark, toggleDark }">
                                <button
                                    @click="toggleDark()"
                                    class="focus:outline-none px-2 py-3 focus:ring-gray-300"
                                >
                                    <component
                                        :is="isDark ? SunIcon : MoonIcon"
                                        class="w-5 h-5"
                                    />
                                </button>
                            </UseDark>
                        </li>
                        <li class="cursor-pointer text-white">
                            <button
                                class="focus:outline-none px-2 py-3 focus:ring-gray-300"
                            >
                                <ChatIcon class="w-5 h-5" />
                            </button>
                        </li>
                        <li class="cursor-pointer text-white">
                            <button
                                class="focus:outline-none px-2 py-3 focus:ring-gray-300"
                            >
                                <CogIcon class="w-5 h-5" />
                            </button>
                        </li>
                        <li class="cursor-pointer text-white">
                            <button
                                class="focus:outline-none px-2 py-3 focus:ring-gray-300"
                            >
                                <LockIcon class="w-5 h-5" />
                            </button>
                        </li>
                    </slot>
                </ul>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.item-link {
    @apply relative flex flex-row items-center h-10 focus:outline-none hover:bg-gray-700 dark:hover:bg-gray-600;
}
.item-text {
    @apply flex h-full items-center hover:translate-x-2 transform-gpu transition-transform duration-200 grow text-sm font-bold tracking-wide truncate;
}
</style>
