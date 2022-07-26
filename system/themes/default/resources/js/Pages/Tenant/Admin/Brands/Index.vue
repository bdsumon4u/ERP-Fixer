<script setup>
import {ref, watch} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {throttle} from "lodash";
import Dropdown from "@/Jetstream/Dropdown.vue";
import Table from "@/DataTable/Table.vue";
import ValidationErrors from "@/Jetstream/ValidationErrors.vue";

const brand = ref();
const form = useForm({
    id: null,
    name: '',
    slug: '',
    image: '',
})

const edit = (record) => {
    console.log(record)
    brand.value = record.name;
    form.id = record.id;
    form.name = record.name;
    form.slug = record.slug;
    form.image = record.image;
}

const destroy = (record) => {
    Inertia.delete(route('tenant.admin.brands.destroy', record));
}

const submit = () => {
    if (form.id) {
        form.post(route('tenant.admin.brands.update', {
            brand: this.form.id,
            _method: 'PATCH',
        }), {
            onSuccess: () => this.form.reset(),
        });
    } else {
        form.post(route('tenant.admin.brands.store'), {
            onSuccess: () => this.form.reset(),
        });
    }
}
</script>
<template>
    <div class="px-4 py-8">
        <div class="flex justify-center flex-wrap lg:space-x-5">
            <div class="w-96">
                <div class="sm:max-w-lg w-full md:p-5 bg-white rounded-xl z-10">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900 align-middle">
                            <span>{{ brand ? 'Edit' : 'Add New' }} Brand</span>
                            <button v-if="brand" @click.prevent="() => edit({})" class="ml-2 border border-red-500">
<!--                                <Icon name="close" class="text-red-500 fill-current" />-->
                            </button>
                        </h2>
                        <p v-if="brand" class="mt-2 text-sm text-gray-500">You're editing "{{ brand }}"</p>
                    </div>
                    <form class="mt-5 space-y-3" @submit.prevent="submit" method="POST">
                        <validation-errors />
                        <div class="grid grid-cols-1 space-y-2">
                            <label class="text-sm font-bold text-gray-500 tracking-wide">Name</label>
                            <input type="text" v-model="form.name" class="text-base p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        </div>
                        <div class="grid grid-cols-1 space-y-2">
                            <label class="text-sm font-bold text-gray-500 tracking-wide">Slug</label>
                            <input type="text" v-model="form.slug" class="text-base p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-1 space-y-2">
<!--                            <ImagePicker name="image" @pick="photo => form.image = photo" />-->
                        </div>

                        <div>
                            <button type="submit" class="my-5 w-full flex justify-center bg-blue-500 text-gray-100 p-4 rounded-md tracking-wide
                                    font-semibold focus:outline-none focus:shadow-outline hover:bg-blue-600 shadow-md cursor-pointer transition ease-in duration-300">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-1 w-full">
                <Table>
                    <template #_image="{column}">
                        <th class="px-3 py-3 text-left text-md font-semibold text-gray-500 capitalize tracking-wider" scope="col">{{ column.label }}</th>
                    </template>
                    <template #image="{record}">
                        <div class="flex items-center px-2 py-1">
                            <img :src="record.image" height="80" width="80" alt="Image">
                        </div>
                    </template>
                    <template #action="{record}">
                        <div class="flex justify-end px-5 gap-x-2">
                            <button class="px-4 py-2 border rounded-md bg-blue-600 text-gray-100 hover:bg-blue-700 hover:text-white" @click.prevent="() => edit(record)">Edit</button>
                            <button class="px-4 py-2 border rounded-md bg-red-600 text-gray-100 hover:bg-red-700 hover:text-white" @click.prevent="destroy(record)">Delete</button>
                        </div>
                    </template>
                </Table>
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "%/default/resources/js/Layouts/Tenant/AdminLayout.vue";

const props = {
    title: "Dashboard",
};

export default {
    layout: (h, page) => {
        return h(AdminLayout, props, () => page);
    },
};
</script>
