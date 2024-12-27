<script setup>
import { ref, watch } from "vue";
const userFormRef = ref();
const outputResponseRef = ref();
const outputResponse = ref();

const submit = async (userFormRef) => {
    let formData = new FormData(userFormRef);
    try {
        const response = await fetch("/api/users", {
            method: "POST",
            body: formData,
        });

        outputResponse.value = await response.json();
    } catch (e) {
        //console.error(e);
        // do not clutter console
    }
};

watch([outputResponse, outputResponseRef], () => {
    if (!(outputResponseRef.value && outputResponse.value)) return;
    const { message, fails = "", user_id = "" } = outputResponse.value;
    outputResponseRef.value.textContent = `${message}${
        fails
            ? "\r\n failed: \r\n\r\n" +
              Object.entries(fails)
                  .map((element) => `${element[0]}:\r\n${element[1]}`)
                  .join("\r\n\r\n")
            : fails
    }${user_id ? "\r\n user ID: " + user_id : user_id}`;
});
</script>

<template>
    <div :class="$style.formContainerDiv">
        <form :ref="(el) => (userFormRef = el)" @submit.prevent="() => submit(userFormRef)">
            <h3 :class="$style.formTitleH3">Add New User</h3>
            <p
                :ref="(el) => (outputResponseRef = el)"
                :class="outputResponse == undefined ? '' : outputResponse.success ? $style.success : $style.failure"
            ></p>
            <p>
                Token: you should get a proper token at
                <span
                    >&nbsp;<a :href="`/api/token`" :target="`_blank`" :rel="`noreferrer noopener`">/api/token</a></span
                >
            </p>
            <input v-model="token" :name="`token`" placeholder="Enter token (128 characters length)" />
            <p></p>
            <p>Name: Username should contain 2-60 characters.</p>
            <input v-model="name" placeholder="name" :name="`name`" />
            <p>Phone: User phone number. Number should start with code of Ukraine +380.</p>
            <input v-model="phone" placeholder="+380XXXXXXXXX" :name="`phone`" />
            <p>Email: User email, must be a valid email according to RFC2822.</p>
            <input v-model="email" placeholder="Valid email e.g. name@example.com" :name="`email`" />
            <p>
                Position ID: User`s position id. You can get list of all positions with their IDs using the API method
                GET
                <span
                    ><a :href="`/api/positions`" :target="`_blank`" :rel="`noreferrer noopener`"
                        >/api/positions</a
                    ></span
                >.
            </p>
            <input v-model="positionId" placeholder="Position ID" :disabled="true" />
            <p>
                Photo: Minimum size of photo 70x70px. The photo format must be jpeg/jpg type. The photo size must not be
                greater than 5 Mb.
            </p>
            <input :type="`file`" :accept="`image/*,.jpeg,.jpg`" :name="`photo`" />

            <div :class="$style.buttonContainerDiv">
                <input :type="`submit`" :value="Submit" />
            </div>
        </form>
    </div>
</template>

<style module>
a {
    color: blue;
}
input {
    padding: 0.3rem;
    border: 1px grey solid;
    width: 100%;
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
.success {
    white-space: pre-line;
    margin: 0.5rem;
    padding: 0.5rem;
    border: 2px green solid;
    color: darkslategrey;
    font-size: 1.5rem;
    border-radius: 1rem;
}
.failure {
    white-space: pre-line;
    margin: 0.5rem;
    padding: 0.5rem;
    border: 3px red solid;
    border-radius: 1rem;
    color: darkred;
    font-size: 1.3rem;
}
</style>
