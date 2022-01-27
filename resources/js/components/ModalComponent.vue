<template>
    <div v-if="error">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ title }}</h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span
                                    aria-hidden="true"
                                    @click="$emit('closeModal')"
                                    >&times;</span
                                >
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>{{ message }}</p>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="$emit('closeModal')"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        error: Boolean,
        title: String,
        message: String,
    },
    data() {
        return {};
    },
    methods: {
        close() {
            this.$emit("close");
        },
    },
};
</script>
<style>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}
</style>
