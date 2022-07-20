<script setup>
import { SearchIcon } from "@heroicons/vue/solid";
import { useMagicKeys } from "@vueuse/core";
import { nextTick, ref, watch } from "vue";
import { useEscOrOutside } from "../../composables/useEscOrOutside";

const props = defineProps({
    id: String,
    searchId: String,
    searchBoxOpen: Boolean,
    openSearchBox: Function,
    closeSearchBox: Function,
});

const modalContent = ref(null);
const searchInput = ref(null);

const { ctrl_k } = useMagicKeys({
    passive: false,
    onEventFired(e) {
        if (e.ctrlKey && e.key === "k" && e.type === "keydown") {
            e.preventDefault();
        }
    },
});

watch(ctrl_k, (open) => open && props.openSearchBox());
useEscOrOutside(modalContent, props.closeSearchBox);

watch(
    () => props.searchBoxOpen,
    (open) => {
        open && nextTick(() => searchInput.value.focus());
    }
);
</script>

<template>
    <!-- Modal backdrop -->
    <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-out duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-show="searchBoxOpen"
            aria-hidden="true"
            class="fixed inset-0 bg-gray-900 bg-opacity-40 z-50 transition-opacity"
        ></div>
    </transition>
    <!-- Modal dialog -->
    <transition
        enter-active-class="transition ease-in-out duration-200"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in-out duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            v-show="searchBoxOpen"
            :id="id"
            aria-modal="true"
            class="fixed inset-0 z-50 overflow-hidden flex items-start top-20 mb-4 justify-center transform px-4 sm:px-6"
            role="dialog"
        >
            <div
                ref="modalContent"
                class="bg-white overflow-auto max-w-2xl w-full max-h-full rounded shadow-lg"
            >
                <!-- Search form -->
                <form class="border-b border-gray-200">
                    <div class="relative">
                        <label :for="searchId" class="sr-only">Search</label>
                        <input
                            :id="searchId"
                            ref="searchInput"
                            class="w-full border-0 focus:ring-transparent placeholder-gray-400 appearance-none py-3 pl-10 pr-4"
                            placeholder="Search Anything…"
                            type="search"
                        />
                        <button
                            aria-label="Search"
                            class="absolute inset-0 right-auto group"
                            type="submit"
                        >
                            <SearchIcon
                                class="w-5 h-5 shrink-0 fill-current text-gray-400 group-hover:text-gray-500 ml-4 mr-2"
                            />
                        </button>
                    </div>
                </form>
                <div class="py-4 px-2">
                    <!-- Recent searches -->
                    <div class="mb-3 last:mb-0">
                        <div
                            class="text-xs font-semibold text-gray-400 uppercase px-2 mb-2"
                        >
                            Recent searches
                        </div>
                        <ul class="text-sm">
                            <li>
                                <Link
                                    class="flex items-center p-2 text-gray-800 hover:text-white hover:bg-indigo-500 rounded group"
                                    to="#0"
                                    @click="() => {}"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 16 16"
                                        width="20"
                                        height="20"
                                        class="w-4 h-4 fill-current text-gray-400 group-hover:text-white group-hover:text-opacity-50 shrink-0 mr-3"
                                    >
                                        <path
                                            d="M15.707 14.293v.001a1 1 0 01-1.414 1.414L11.185 12.6A6.935 6.935 0 017 14a7.016 7.016 0 01-5.173-2.308l-1.537 1.3L0 8l4.873 1.12-1.521 1.285a4.971 4.971 0 008.59-2.835l1.979.454a6.971 6.971 0 01-1.321 3.157l3.107 3.112zM14 6L9.127 4.88l1.521-1.28a4.971 4.971 0 00-8.59 2.83L.084 5.976a6.977 6.977 0 0112.089-3.668l1.537-1.3L14 6z"
                                        ></path>
                                    </svg>
                                    <span
                                        >Form Builder - 23 hours on-demand
                                        video</span
                                    >
                                </Link>
                            </li>
                            <li>
                                <Link
                                    class="flex items-center p-2 text-gray-800 hover:text-white hover:bg-indigo-500 rounded group"
                                    to="#0"
                                    @click="() => {}"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 16 16"
                                        width="20"
                                        height="20"
                                        class="w-4 h-4 fill-current text-gray-400 group-hover:text-white group-hover:text-opacity-50 shrink-0 mr-3"
                                    >
                                        <path
                                            d="M15.707 14.293v.001a1 1 0 01-1.414 1.414L11.185 12.6A6.935 6.935 0 017 14a7.016 7.016 0 01-5.173-2.308l-1.537 1.3L0 8l4.873 1.12-1.521 1.285a4.971 4.971 0 008.59-2.835l1.979.454a6.971 6.971 0 01-1.321 3.157l3.107 3.112zM14 6L9.127 4.88l1.521-1.28a4.971 4.971 0 00-8.59 2.83L.084 5.976a6.977 6.977 0 0112.089-3.668l1.537-1.3L14 6z"
                                        ></path>
                                    </svg>
                                    <span
                                        >Master Digital Marketing Strategy
                                        course</span
                                    >
                                </Link>
                            </li>
                            <li>
                                <Link
                                    class="flex items-center p-2 text-gray-800 hover:text-white hover:bg-indigo-500 rounded group"
                                    to="#0"
                                    @click="() => {}"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 16 16"
                                        width="20"
                                        height="20"
                                        class="w-4 h-4 fill-current text-gray-400 group-hover:text-white group-hover:text-opacity-50 shrink-0 mr-3"
                                    >
                                        <path
                                            d="M15.707 14.293v.001a1 1 0 01-1.414 1.414L11.185 12.6A6.935 6.935 0 017 14a7.016 7.016 0 01-5.173-2.308l-1.537 1.3L0 8l4.873 1.12-1.521 1.285a4.971 4.971 0 008.59-2.835l1.979.454a6.971 6.971 0 01-1.321 3.157l3.107 3.112zM14 6L9.127 4.88l1.521-1.28a4.971 4.971 0 00-8.59 2.83L.084 5.976a6.977 6.977 0 0112.089-3.668l1.537-1.3L14 6z"
                                        ></path>
                                    </svg>
                                    <span>Dedicated forms for products</span>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    class="flex items-center p-2 text-gray-800 hover:text-white hover:bg-indigo-500 rounded group"
                                    to="#0"
                                    @click="() => {}"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 16 16"
                                        width="20"
                                        height="20"
                                        class="w-4 h-4 fill-current text-gray-400 group-hover:text-white group-hover:text-opacity-50 shrink-0 mr-3"
                                    >
                                        <path
                                            d="M15.707 14.293v.001a1 1 0 01-1.414 1.414L11.185 12.6A6.935 6.935 0 017 14a7.016 7.016 0 01-5.173-2.308l-1.537 1.3L0 8l4.873 1.12-1.521 1.285a4.971 4.971 0 008.59-2.835l1.979.454a6.971 6.971 0 01-1.321 3.157l3.107 3.112zM14 6L9.127 4.88l1.521-1.28a4.971 4.971 0 00-8.59 2.83L.084 5.976a6.977 6.977 0 0112.089-3.668l1.537-1.3L14 6z"
                                        ></path>
                                    </svg>
                                    <span>Product Update - Q4 2021</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <!-- Recent pages -->
                    <div class="mb-3 last:mb-0">
                        <div
                            class="text-xs font-semibold text-gray-400 uppercase px-2 mb-2"
                        >
                            Recent pages
                        </div>
                        <ul class="text-sm">
                            <li>
                                <Link
                                    class="flex items-center p-2 text-gray-800 hover:text-white hover:bg-indigo-500 rounded group"
                                    to="#0"
                                    @click="() => {}"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 16 16"
                                        width="20"
                                        height="20"
                                        class="w-4 h-4 fill-current text-gray-400 group-hover:text-white group-hover:text-opacity-50 shrink-0 mr-3"
                                    >
                                        <path
                                            d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h8l5-5V1c0-.6-.4-1-1-1zM3 2h10v8H9v4H3V2z"
                                        ></path>
                                    </svg>
                                    <span
                                        ><span
                                            class="font-medium text-gray-800 group-hover:text-white"
                                            >Messages</span
                                        >
                                        - Conversation / … / Mike Mills</span
                                    >
                                </Link>
                            </li>
                            <li>
                                <Link
                                    class="flex items-center p-2 text-gray-800 hover:text-white hover:bg-indigo-500 rounded group"
                                    to="#0"
                                    @click="() => {}"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 16 16"
                                        width="20"
                                        height="20"
                                        class="w-4 h-4 fill-current text-gray-400 group-hover:text-white group-hover:text-opacity-50 shrink-0 mr-3"
                                    >
                                        <path
                                            d="M14 0H2c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h8l5-5V1c0-.6-.4-1-1-1zM3 2h10v8H9v4H3V2z"
                                        ></path>
                                    </svg>
                                    <span
                                        ><span
                                            class="font-medium text-gray-800 group-hover:text-white"
                                            >Messages</span
                                        >
                                        - Conversation / … / Eva Patrick</span
                                    >
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
