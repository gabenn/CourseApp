<template>
    <div>
        <ModalComponent
            v-if="error"
            :error="error"
            :message="errorMessage"
            :title="errorTitle"
            @closeModal="closeModal"
        >
        </ModalComponent>
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
                ref="wordsQuantityInput"
                placeholder="Words Quantity"
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
    </div>
</template>
<script>
import WordInput from "./WordInputComponent";
import ModalComponent from "./ModalComponent";

export default {
    components: {
        WordInput,
        ModalComponent,
    },
    data() {
        return {
            polishWordsInputs: [],
            englishWordsInputs: [],
            polishWords: [],
            englishWords: [],
            error: false,
            errorTitle: "",
            errorMessage: "",
        };
    },
    methods: {
        displayWordsInputs() {
            this.polishWordsInputs.forEach((word) => {});
            this.englishWordsInputs.forEach((word) => {});

            this.polishWords = [];
            this.englishWords = [];
            this.polishWordsInputs = [];
            this.englishWordsInputs = [];

            let wordsQuantity = this.$refs.wordsQuantityInput.value;
            wordsQuantity = wordsQuantity > 0 ? wordsQuantity : 1;
            wordsQuantity = wordsQuantity < 20 ? wordsQuantity : 20;

            for (let i = 0; i < wordsQuantity; i++) {
                const polishWordInput = "";
                this.polishWordsInputs.push(polishWordInput);
                this.englishWordsInputs.push(polishWordInput);
            }
        },
        readAllWords() {
            this.polishWords = [];
            this.englishWords = [];

            this.$refs.polishWordsInputs.forEach((input) =>
                this.polishWords.push(input.$el.value)
            );

            this.$refs.englishWordsInputs.forEach((input) =>
                this.englishWords.push(input.$el.value)
            );
        },
        validate() {
            console.log(this.polishWords);
            console.log(this.englishWords);
            if (this.$refs.courseNameInput.value === "") {
                this.error = true;
                this.errorTitle = "Course Name Error";
                this.errorMessage = "Course Name input is empty";
            }
            if (
                this.polishWords.length === 0 ||
                this.englishWords.length === 0
            ) {
                this.error = true;
                this.errorTitle = "Words Error";
                this.errorMessage = "Create Some Words";
            }
            this.polishWords.forEach((word) => {
                console.log("pl", word);
                if (word === "") {
                    this.error = true;
                    this.errorTitle = "Words Error";
                    this.errorMessage = "Fill All Inputs";
                }
            });
            this.englishWords.forEach((word) => {
                console.log("eng", word);
                if (word === "") {
                    this.error = true;
                    this.errorTitle = "Words Error";
                    this.errorMessage = "Fill All Inputs";
                }
            });
        },
        submitForm() {
            this.validate();
            if (!this.error) {
                const courseData = {
                    name: this.$refs.courseNameInput.value,
                };
                axios
                    .post(`${window.location.origin}/api/courses`, courseData)
                    .then((res) => {
                        const data = {
                            words: [],
                            course_id: 0,
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
            }
        },
        closeModal() {
            this.error = false;
        },
    },
    computed: {},
    mounted() {
        this.$refs.wordsQuantityInput.value = 1;
        this.displayWordsInputs();
    },
};
</script>
