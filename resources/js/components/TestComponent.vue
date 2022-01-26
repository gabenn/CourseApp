<template>
    <div class="flex flex-column">
        <div>
            <h3>Word {{ this.activeWord }}/{{ this.wordsArray.length }}</h3>
        </div>
        <div class="flex justify-content-center">
            <div>
                <p class="form-control">
                    Polish Word:
                    {{ this.wordsArray[this.activeWord - 1].polish }}
                </p>
            </div>
            <div>
                <input
                    type="text"
                    class="form-control"
                    placeholder="English Word"
                    ref="answerInput"
                />
            </div>
        </div>
        <div class="flex justify-content-center">
            <button class="btn btn-primary" @click="confirm">Confirm</button>
        </div>
    </div>
</template>
<script>
export default {
    props: ["course", "words"],
    data() {
        return {
            goodAnswers: 0,
            activeWord: 1,
            wordsArray: [],
        };
    },
    methods: {
        confirm() {
            if (
                this.$refs.answerInput.value ===
                this.wordsArray[this.activeWord - 1].english
            ) {
                this.goodAnswers++;
                this.$refs.answerInput.value = "";
                this.$refs.answerInput.focus();
            }
            if (this.activeWord < this.wordsArray.length) this.activeWord++;
            else {
            }
        },
    },
    created() {
        this.wordsArray = JSON.parse(this.words);
    },
};
</script>
