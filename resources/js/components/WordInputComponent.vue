<template>
    <input
        type="text"
        :placeholder="placeholderText"
        v-bind:value="value"
        v-on:input="$emit('input', $event.target.value)"
        class="form-control mx-2"
    />
</template>
<script>
export default {
    props: {
        placeholderText: String,
        value: String,
    },
};
</script>
