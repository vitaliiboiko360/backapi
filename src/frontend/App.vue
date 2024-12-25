<script setup>
import Users from "./users/Users.vue";
</script>

<template>
    <div :class="$style.outerDiv">
        <div :class="$style.innerDiv">
            <h2>Users</h2>
            <Users />
        </div>
    </div>
</template>

<style module>
.outerDiv {
    display: flex;
    flex-direction: column;
}

.innerDiv {
    min-width: 70%;
    max-width: 30%;
    align-self: center;
}
</style>
