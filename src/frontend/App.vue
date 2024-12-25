<script setup></script>

<template>
    <div :class="$style.outerDiv">
        <div :class="$style.innerDiv">
            <h2>Users</h2>
            <p>test page</p>
        </div>
    </div>
</template>

<style module>
.outerDiv {
    display: flex;
    flex-direction: column;
}

.innerDiv {
    min-width: 30%;
    max-width: 30%;
    align-self: center;
}
</style>
