<script setup>
import User from "./User.vue";
import CreateUser from "./CreateUser.vue";
</script>

<template>
    <div>
        <User v-for="user in 5" :key="user" />
    </div>
    <div>
        <CreateUser />
    </div>
</template>
