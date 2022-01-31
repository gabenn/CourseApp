<template>
    <div>
        <form
            method="POST"
            action="/api/words"
            enctype="application/x-www-form-urlencoded"
        >
            <input
                type="text"
                placeholder="Course Name"
                ref="courseNameInput"
            />
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
            <input type="button" @click="submitForm" value="Add Course" />
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
        },
        submitForm() {
            const courseData = {
                name: this.$refs.courseNameInput.value,
            };
            axios
                .post(`http://crudapi-master.test/api/courses`, courseData)
                .then((res) => console.log(response))
                .catch((error) =>
                    error != undefined ? console.log(error.response) : null
                );

            for (let i = 0; i < this.polishWords.length; i++) {
                const wordsData = {
                    course_id: 1, // jak dodawać do course który dodaje wyżejS
                    polish: this.polishWords[i],
                    english: this.englishWords[i],
                };

                axios
                    .post(`http://crudapi-master.test/api/words`, wordsData)
                    .then((res) => console.log(response))
                    .catch((error) => console.log(error.response));
            }
        },
    },
    computed: {},
};
</script>
