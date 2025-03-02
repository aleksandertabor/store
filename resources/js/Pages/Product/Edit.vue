<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/Layout.vue";
import { onMounted, ref } from "vue";
import { useCurrencyInput } from "vue-currency-input";

onMounted(async () => {
    await getProduct();
});

const form = ref({
    name: "",
    description: "",
    price: 0,
    stock: 0,
    rank: 1,
    image: null,
});

const imagePreview = ref(null);

const errors = ref({
    name: [],
    description: [],
    price: [],
    stock: [],
    rank: [],
    image: [],
});

const loading = ref(false);
const success = ref(false);
const error = ref(false);

const getProduct = async () => {
    try {
        const result = await axios.get(
            route("api.products.show", { product: route().params.product }),
        );
        const data = result.data;

        form.value = data.data;
        imagePreview.value = data.data.image;
        form.value.image = null;
    } catch (e) {
    } finally {
    }
};

const submitForm = async () => {
    loading.value = true;
    success.value = false;

    try {
        let formData = new FormData();
        formData.append("name", form.value.name);
        formData.append("description", form.value.description);
        formData.append("stock", form.value.stock);
        formData.append("rank", form.value.rank);
        formData.append("price", form.value.price.replace(/,/g, ""));
        formData.append("_method", "PATCH");
        if (form.value.image) {
            formData.append("image", form.value.image);
        }

        const response = await axios.post(
            route("api.products.update", { product: route().params.product }),
            formData,
            {
                headers: { "Content-Type": "multipart/form-data" },
            },
        );

        if (response.status === 200) {
            errors.value = {
                name: [],
                description: [],
                price: [],
                stock: [],
                rank: [],
                image: [],
            };

            success.value = true;
        }
    } catch (e) {
        if (e.response.status === 422) {
            errors.value = e.response.data.errors;
        }
    } finally {
        loading.value = false;
    }
};

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const { inputRef } = useCurrencyInput({
    currency: "PLN",
    precision: 2,
    valueRange: { min: 0 },
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Edit product" />

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit product
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <v-container>
                            <v-card class="pa-5" elevation="2">
                                <v-card-title>Edit Product</v-card-title>
                                <v-card-text>
                                    <v-form @submit.prevent="submitForm">
                                        <v-text-field
                                            class="mb-2"
                                            v-model="form.name"
                                            label="Name"
                                            :error-messages="errors.name"
                                            required
                                        ></v-text-field>

                                        <v-textarea
                                            class="mb-2"
                                            v-model="form.description"
                                            label="Description"
                                            :error-messages="errors.description"
                                            required
                                        ></v-textarea>

                                        <v-text-field
                                            class="mb-2"
                                            v-model="form.stock"
                                            label="Stock"
                                            type="number"
                                            :error-messages="errors.stock"
                                            required
                                        ></v-text-field>

                                        <v-text-field
                                            class="mb-2"
                                            v-model="form.rank"
                                            label="Rank"
                                            type="number"
                                            :error-messages="errors.rank"
                                            required
                                        ></v-text-field>

                                        <v-text-field
                                            class="mb-2"
                                            v-model="form.price"
                                            label="Price"
                                            type="text"
                                            ref="inputRef"
                                            :error-messages="errors.price"
                                            required
                                        ></v-text-field>

                                        <v-img
                                            v-if="imagePreview"
                                            :src="imagePreview"
                                            max-width="200"
                                            class="my-3"
                                        ></v-img>

                                        <v-file-input
                                            v-model="form.image"
                                            label="Upload Image"
                                            accept="image/*"
                                            @change="handleFileUpload"
                                            clearable
                                            @click:clear="imagePreview = null"
                                            :error-messages="errors.image"
                                        ></v-file-input>

                                        <v-btn
                                            class="mt-2"
                                            type="submit"
                                            color="primary"
                                            :loading="loading"
                                        >Edit
                                        </v-btn>
                                    </v-form>
                                </v-card-text>
                                <v-alert
                                    v-if="success"
                                    text="The product has been updated successfully"
                                    title="Success"
                                    type="success"
                                ></v-alert>
                                <v-alert
                                    v-if="error"
                                    text="Something went wrong. Please try again."
                                    title="Error"
                                    type="error"
                                ></v-alert>
                            </v-card>
                        </v-container>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
