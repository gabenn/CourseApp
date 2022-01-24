<template>
    <div>
        <form
            method="POST"
            action="/api/courses"
            enctype="application/x-www-form-urlencoded"
        >
            <input type="text" name="courseName" placeholder="Course Name" />
            <input type="hidden" name="polishWords" id="polishWordsInput" />
            <input type="hidden" name="englishWords" id="englishWordsInput" />
            <input
                min="1"
                max="20"
                type="number"
                id="wordsQuantityInput"
                placeholder="quantity"
                @change="displayWordsInputs"
            />
            <div id="wordsBox" class="flex flex-row">
                <div id="polishWordsBox" v-for="input in polishWordsInputs">
                    <WordInput
                        v-bind:placeholderText="`Polish Word`"
                        @read="readAllWords"
                        ref="polishWordsInputs"
                    ></WordInput>
                </div>
                <div id="englishWordsBox" v-for="input in englishWordsInputs">
                    <WordInput
                        v-bind:placeholderText="`English Word`"
                        @read="readAllWords"
                        ref="englishWordsInputs"
                    ></WordInput>
                </div>
            </div>
            <input type="submit" value="Add Course" />
        </form>
    </div>
</template>
<script>
import WordInput from "./WordInputComponent";

export default {
    components: {
        WordInput,
    },
    data() {
        return {
            polishWordsInputs: [],
            englishWordsInputs: [],
            polishWords: [],
            englishWords: [],
        };
    },
    methods: {
        displayWordsInputs({ target }) {
            this.polishWordsInputs.forEach((word) => {});
            this.englishWordsInputs.forEach((word) => {});

            this.polishWords.length = 0;
            this.englishWords.length = 0;
            this.polishWordsInputs.length = 0;
            this.englishWordsInputs.length = 0;

            let wordsQuantity = target.value;
            wordsQuantity = wordsQuantity > 0 ? wordsQuantity : 1;
            wordsQuantity = wordsQuantity < 20 ? wordsQuantity : 20;

            for (let i = 0; i < wordsQuantity; i++) {
                const polishWordInput = {
                    id: Math.floor(Math.random() * 100),
                };
                this.polishWordsInputs.push(polishWordInput);
                this.englishWordsInputs.push(polishWordInput);
            }
        },
        readAllWords() {
            this.polishWords.length = 0;
            this.englishWords.length = 0;

            this.$refs.polishWordsInputs.forEach((input) => {
                this.polishWords.push(input.$el.value);
            });

            this.$refs.englishWordsInputs.forEach((input) =>
                this.englishWords.push(input.$el.value)
            );

            document.getElementById("polishWordsInput").value =
                this.polishWords.join("|");
            document.getElementById("englishWordsInput").value =
                this.englishWords.join("|");
        },
    },
    computed: {},
};
</script>
