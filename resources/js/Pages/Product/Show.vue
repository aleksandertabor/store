<script setup>
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import Layout from "@/Layouts/Layout.vue";

const product = ref({});

onMounted(async () => {
    await getProduct();
});

const getProduct = async () => {
    try {
        const result = await axios.get(
            route("api.products.show", { product: route().params.product }),
        );
        const data = result.data;

        product.value = data.data;
    } catch (e) {
    } finally {
    }
};
</script>
<template>
    <Layout>
        <Head :title="product.name" />

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ product.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <v-container>
                        <v-row justify="center">
                            <v-col cols="12" md="8" lg="6">
                                <v-card>
                                    <v-img
                                        :src="product.image"
                                        height="300px"
                                        cover
                                    ></v-img>
                                    <v-card-title class="text-h5">{{
                                            product.name
                                        }}</v-card-title>
                                    <v-card-subtitle class="text-grey-darken-1">
                                        ID: {{ product.id }}
                                    </v-card-subtitle>
                                    <v-card-text>
                                        <p>
                                            <strong>Description:</strong>
                                            {{ product.description }}
                                        </p>
                                        <p>
                                            <strong>Price:</strong>
                                            {{ product.price }} PLN
                                        </p>
                                        <p>
                                            <strong>Stock:</strong>
                                            {{ product.stock }}
                                        </p>
                                        <p>
                                            <strong>Rank:</strong>
                                            {{ product.rank }}
                                        </p>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </div>
        </div>
    </Layout>
</template>
