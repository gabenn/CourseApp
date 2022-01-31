<template>
    <input
        type="text"
        :placeholder="plholder"
        @input="$emit('read')"
        class="form-control"
    />
</template>
<script>
export default {
    props: {
        placeholderText: String,
    },
    data() {
        return {
            plholder: this.placeholderText,
        };
    },
};
</script>
