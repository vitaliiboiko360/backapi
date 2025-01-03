<script setup>
import { defineProps } from "vue";
const {
    name = "",
    phone = "",
    email = "",
    photo = "",
    position = "",
} = defineProps(["name", "phone", "email", "photo", "position"]);
</script>

<template>
    <div :class="$style.divOuter">
        <div><img :src="photo" :alt="`No photo yet`" /></div>
        <div>
            <div :class="$style.divUserCard">
                <p>Name: {{ name }}</p>
                <p>Phone: {{ phone }}</p>
                <p>Email: {{ email }}</p>
                <p>Position: {{ position }}</p>
            </div>
        </div>
    </div>
</template>

<style module>
img {
    min-width: 5rem;
    min-height: 5rem;
    max-width: 8rem;
    max-height: 8rem;
}
p {
    margin: 0 0.5rem 0.5rem;
}
.divOuter {
    display: flex;
    margin-top: 1rem;
    padding-top: 0.5rem;
}
.divUserCard {
    display: flex;
    flex-direction: column;
}
</style>
