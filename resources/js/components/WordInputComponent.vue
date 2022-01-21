<template>
    <input type="text" :placeholder="plholder" @input="$emit('read')" />
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
