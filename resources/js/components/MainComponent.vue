<template>
    <div>
        <WordInput
            placeholderText="polishWords"
            :change="readAllWords"
        ></WordInput>
        <button @click="readAllWords" type="button" id="readBtn">Read</button>

        <form
            method="POST"
            action="/api/courses"
            enctype="application/x-www-form-urlencoded"
        >
            <button @click="displayWordsInputs" type="button" id="displayBtn">
                Display Words Inputs
            </button>
            <input type="text" name="courseName" placeholder="Course Name" />
            <input type="hidden" name="polishWords" id="polishWordsInput" />
            <input type="hidden" name="englishWords" id="englishWordsInput" />
            <input
                min="1"
                max="20"
                type="number"
                id="wordsQuantityInput"
                placeholder="Words Quantity"
                @change="displayWordsInputs"
            />
            <div id="wordsBox" class="flex flex-row">
                <div id="polishWordsBox" v-for="input in polishWordsInputs">
                    {{ input }}
                </div>
                <div id="englishWordsBox" v-for="input in polishWordsInputs">
                    {{ input }}
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
            console.log("display");
            this.polishWordsInputs.forEach((word) => {});
            this.englishWordsInputs.forEach((word) => {});

            this.polishWords.length = 0;
            this.englishWords.length = 0;
            this.polishWordsInputs.length = 0;
            this.englishWordsInputs.length = 0;

            let wordsQuantity = target.value;
            wordsQuantity = wordsQuantity > 0 ? wordsQuantity : 1;
            wordsQuantity = wordsQuantity < 20 ? wordsQuantity : 20;
            // target.value = wordsQuantity;
            for (let i = 0; i < wordsQuantity; i++) {
                const polishWordInput = this.WordInput;
                // this.polishWordsInput.push(polishWordInput);
                console.log(polishWordInput);
            }
        },
        readAllWords() {
            console.log("read");

            // this.polishWords.length = 0;
            // this.englishWords.length = 0;

            // this.polishWordsInputs.forEach((input) =>
            //     this.polishWords.push(input.value)
            // );

            // this.englishWordsInputs.forEach((input) =>
            //     this.englishWordsthis.push(input.value)
            // );

            // document.getElementById("polishWordsInput").value =
            //     this.polishWords.join("|");
            // document.getElementById("englishWordsInput").value =
            //     this.englishWords.join("|");
        },
    },
    computed: {},
};
</script>
