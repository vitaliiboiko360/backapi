<script setup>
import { onMounted, ref, watch } from "vue";
import User from "./User.vue";
import CreateUser from "./CreateUser.vue";
const outputResponse = ref();
const currentPage = ref(1);
const DEFAULT_EMPTY_NUMBER_OF_USERS = 5;
const DEFAULT_NUMBER_OF_USERS_TO_SHOW = 6;

const getUsers = async () => {
    try {
        const response = await fetch(`/api/users?count=${DEFAULT_NUMBER_OF_USERS_TO_SHOW}&page=${currentPage.value}`);
        outputResponse.value = await response.json();
        console.log(JSON.stringify(outputResponse.value));
    } catch (e) {
        /** do not clutter console */
    }
};

onMounted(getUsers);
watch(currentPage, getUsers);
</script>

<template>
    <div>
        <User
            v-for="(user, index) in outputResponse?.users ?? DEFAULT_EMPTY_NUMBER_OF_USERS"
            :key="index"
            :name="user.name"
            :email="user.email"
            :phone="user.phone"
            :photo="user.photo"
        />
    </div>
    <div>
        <button
            :disabled="outputResponse?.links?.prev_url == null ? true : false"
            @click="
                () => {
                    console.log(outputResponse.links.prev_url);
                    currentPage = currentPage - 1;
                }
            "
        >
            &lt&lt&nbspPrev&nbspPage</button
        >&nbsp
        <button
            :disabled="outputResponse?.links?.next_url == null ? true : false"
            @click="
                () => {
                    console.log(outputResponse.links.next_url);
                    currentPage = Math.min(currentPage + 1, outputResponse.total_pages);
                }
            "
        >
            Next&nbspPage&nbsp&gt&gt
        </button>
    </div>
    <div>
        <CreateUser />
    </div>
</template>

<style module>
button {
    font-size: 2rem;
    margin: 1rem 0;
}
</style>
