<script setup>
import Welcome from "@/Jetstream/Welcome.vue";
</script>

<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <Welcome />
            </div>
        </div>
    </div>
</template>

<script>
import SellerLayout from "%/default/resources/js/Layouts/Tenant/SellerLayout.vue";

const props = {
    title: "Dashboard",
};

export default {
    layout: (h, page) => {
        return h(SellerLayout, props, () => page);
    },
};
</script>
