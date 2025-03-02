<script setup>
import { Head, usePage, router } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import { throttle } from "lodash";
import Layout from "@/Layouts/Layout.vue";

const searchQuery = ref(usePage().props.query || "");
const currentPage = ref(usePage().props.currentPage || 1);
const products = ref([]);

onMounted(async () => {
    await getProducts();
});

const getProducts = async () => {
    try {
        error.value = false;
        const result = await axios.get(
            route("api.products.index", {
                q: searchQuery.value,
                page: currentPage.value,
            }),
        );
        const data = result.data;

        products.value = data.data;
        lastPage.value = data.meta.last_page;
    } catch (e) {
        error.value = true;
    } finally {
    }
};

const deleteProduct = async (id) => {
    try {
        error.value = false;
        await axios.delete(route("api.products.destroy", id));
        await getProducts();
    } catch (e) {
        error.value = true;
    } finally {
    }
};

const showProduct = async (event, { item }) => {
    router.get(route("products.show", { product: item.id }));
};

const updateRouter = async () => {
    const searchParams = new URLSearchParams({
        page: currentPage.value,
        q: searchQuery.value,
    });
    router.push({
        url: "/?" + searchParams.toString(),
        component: "Product/Index",
        props: (currentProps) => ({
            ...currentProps,
            currentPage: currentPage.value,
            query: searchQuery.value,
        }),
        preserveState: true,
        preserveScroll: true,
    });
};

watch(searchQuery, async function () {
    if (searchQuery.value === null) {
        searchQuery.value = "";
    }

    currentPage.value = 1;
    await updateRouter();
    await getProducts();
});
watch(
    currentPage,
    throttle(async function () {
        await updateRouter();
        await getProducts();
    }, 800),
);

const headers = [
    { title: "Id", value: "id" },
    { title: "Image", value: "image", align: "center" },
    { title: "Name", value: "name" },
    { title: "Price", value: "price", align: "center" },
    { title: "Stock", value: "stock", align: "center" },
    { title: "Rank", value: "rank", align: "center" },
    { title: "Actions", value: "actions", sortable: false },
    { title: "", key: "data-table-expand" },
];
const expanded = ref([]);
const lastPage = ref(1);
const loading = ref(false);
const error = ref(false);
</script>

<template>
    <Layout>
        <Head title="Products" />

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Products
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <v-container>
                        <v-alert
                            v-if="error"
                            text="Something went wrong. Please try again."
                            title="Error"
                            type="error"
                        ></v-alert>
                        <v-card class="pa-5" elevation="2">
                            <v-card-title>
                                <v-btn
                                    color="primary"
                                    dark
                                    :href="route('products.create')"
                                >
                                    <v-icon left>mdi-plus</v-icon> Add product
                                </v-btn>
                                <v-text-field
                                    class="mt-5"
                                    v-model="searchQuery"
                                    label="Search product..."
                                    prepend-inner-icon="mdi-magnify"
                                    clearable
                                ></v-text-field>
                            </v-card-title>

                            <v-card-text>
                                <v-data-table
                                    v-model:expanded="expanded"
                                    :headers="headers"
                                    :items="products"
                                    :loading="loading"
                                    class="elevation-1"
                                    hide-default-footer
                                    @click:row="showProduct"
                                >
                                    <template v-slot:item.price="{ item }">
                                        {{ item.price }} PLN
                                    </template>

                                    <template v-slot:item.image="{ item }">
                                        <v-img
                                            v-if="item.image"
                                            :src="item.image"
                                            max-width="100"
                                        ></v-img>
                                        <span v-else>No image</span>
                                    </template>

                                    <template v-slot:item.stock="{ item }">
                                        <v-chip
                                            :color="
                                                item.stock ? 'green' : 'red'
                                            "
                                            :text="
                                                item.stock
                                                    ? '' + item.stock
                                                    : '0'
                                            "
                                            class="text-uppercase"
                                            size="small"
                                            label
                                        ></v-chip>
                                    </template>

                                    <template v-slot:item.actions="{ item }">
                                        <div class="flex">
                                            <v-btn
                                                icon
                                                size="x-small"
                                                color="blue"
                                                class="mr-2"
                                                :href="
                                                    route(
                                                        'products.edit',
                                                        item.id,
                                                    )
                                                "
                                                @click.stop
                                            >
                                                <v-icon>mdi-pencil</v-icon>
                                            </v-btn>
                                            <v-btn
                                                icon
                                                size="x-small"
                                                color="red"
                                                @click.stop="
                                                    deleteProduct(item.id)
                                                "
                                            >
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </div>
                                    </template>

                                    <template
                                        v-slot:item.data-table-expand="{
                                            internalItem,
                                            isExpanded,
                                            toggleExpand,
                                        }"
                                    >
                                        <td>
                                            <v-btn
                                                :icon="
                                                    isExpanded(internalItem)
                                                        ? 'mdi-chevron-up'
                                                        : 'mdi-chevron-down'
                                                "
                                                size="small"
                                                variant="plain"
                                                @click.stop="
                                                    toggleExpand(internalItem)
                                                "
                                            ></v-btn>
                                        </td>
                                    </template>
                                    <template
                                        v-slot:expanded-row="{ columns, item }"
                                    >
                                        <tr>
                                            <td :colspan="columns.length">
                                                {{ item.description }}
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>

                                <v-pagination
                                    v-model="currentPage"
                                    :length="lastPage"
                                    class="my-4"
                                ></v-pagination>
                            </v-card-text>
                        </v-card>
                    </v-container>
                </div>
            </div>
        </div>
    </Layout>
</template>
