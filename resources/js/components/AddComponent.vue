<template>
    <form
        method="POST"
        action="/api/words"
        enctype="application/x-www-form-urlencoded"
    >
        <div>
            <label for="courseNameInput">Course Name </label>
            <input
                class="form-control"
                type="text"
                placeholder="Course Name"
                id="courseNameInput"
                ref="courseNameInput"
            />
        </div>
        <div>
            <label for="wordsQuantityInput">Words Quantity</label>
            <input
                min="1"
                max="20"
                type="number"
                id="wordsQuantityInput"
                placeholder="0"
                @change="displayWordsInputs"
                class="form-control"
            />
        </div>
        <div id="wordsBox" class="flex flex-row mt-5 mb-5">
            <ol class="flex flex-column" id="polishWordsBox">
                <li v-for="input in polishWordsInputs">
                    <WordInput
                        v-bind:placeholderText="`Polish Word`"
                        @read="readAllWords"
                        ref="polishWordsInputs"
                    ></WordInput>
                </li>
            </ol>
            <ol class="flex flex-column" id="englishWordsBox">
                <li v-for="input in englishWordsInputs">
                    <WordInput
                        v-bind:placeholderText="`English Word`"
                        @read="readAllWords"
                        ref="englishWordsInputs"
                    ></WordInput>
                </li>
            </ol>
        </div>
        <input
            type="button"
            @click="submitForm"
            value="Add Course"
            class="btn btn-primary"
        />
    </form>
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
                const polishWordInput = "";
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
        },
        submitForm() {
            const courseData = {
                name: this.$refs.courseNameInput.value,
            };
            axios
                .post(`${window.location.origin}/api/courses`, courseData)
                .then((res) => {
                    const data = {
                        words: [],
                    };
                    for (let i = 0; i < this.polishWords.length; i++) {
                        data.words.push({
                            polish: this.polishWords[i],
                            english: this.englishWords[i],
                        });
                    }
                    axios
                        .post(`${window.location.origin}/api/words`, data)
                        .then((res) => console.log(response))
                        .catch((error) => console.log(error.response));
                })
                .catch((error) =>
                    error != undefined ? console.log(error.response) : null
                )
                .then(() => (window.location.href = "/courses"));
        },
    },
    computed: {},
};
</script>
