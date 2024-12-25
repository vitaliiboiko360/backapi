<script setup></script>

<template>
    <div :class="$style.formContainerDiv">
        <h3 :class="$style.formTitleH3">Add New User</h3>
        <p>Name: Username should contain 2-60 characters.</p>
        <input v-model="name" placeholder="name" />
        <p>Phone: User phone number. Number should start with code of Ukraine +380.</p>
        <input v-model="phone" placeholder="+380XXXXXXXXX" />
        <p>Email: User email, must be a valid email according to RFC2822.</p>
        <input v-model="email" placeholder="Valid email e.g. name@example.com" />
        <p>
            Position ID: User`s position id. You can get list of all positions with their IDs using the API method GET
            api/positions.
        </p>
        <input v-model="positionId" placeholder="Position ID" />
        <p>
            Photo: Minimum size of photo 70x70px. The photo format must be jpeg/jpg type. The photo size must not be
            greater than 5 Mb.
        </p>
        <input :type="`file`" :accept="`image/*,.jpeg,.jpg`" />

        <div :class="$style.buttonContainerDiv">
            <button>Submit</button>
        </div>
    </div>
</template>

<style module>
input {
    padding: 0.3rem;
    border: 1px grey solid;
}
.formContainerDiv {
    margin-bottom: 2rem;
}
.formTitleH3 {
    margin-top: 1rem;
    margin-bottom: 1rem;
}
.buttonContainerDiv {
    margin: 0.3rem;
}
</style>
