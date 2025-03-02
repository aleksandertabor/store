import axios from "axios";
import { router } from "@inertiajs/vue3";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.interceptors.response.use(
    (response) => response,
    (error) => errorResponseHandler(error),
);

const HTTP_BAD_REQUEST = 400;
const HTTP_UNAUTHORIZED = 401;
const HTTP_UNPROCESSABLE_ENTITY = 422;

const errorResponseHandler = async (error) => {
    const { status } = error.response || {};

    if (status === HTTP_BAD_REQUEST || status === HTTP_UNPROCESSABLE_ENTITY) {
        return Promise.reject(error);
    }
    if (status === HTTP_UNAUTHORIZED) {
        router.get(route("login"));

        return Promise.reject(error);
    }

    // Send to log tracker
    console.error(error);

    return Promise.reject(error);
};
