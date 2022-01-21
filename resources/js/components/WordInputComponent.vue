<template>
    <input type="text" :placeholder="placeholder" />
</template>
<script>
export default {
    props: ["placeholderText"],
    data() {
        return {
            placeholder: this.placeholderText,
        };
    },
    methods: {},
    computed: {},
};
</script>
