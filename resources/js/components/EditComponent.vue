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
            value="Edit Course"
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
    props: ["course", "words"],
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

            this.$refs.polishWordsInputs.forEach((input) => {
                this.polishWords.push(input.$el.value);
            });

            this.$refs.englishWordsInputs.forEach((input) =>
                this.englishWords.push(input.$el.value)
            );
        },
        submitForm() {
            this.readAllWords();
            this.validate();
            if (!this.error) {
                const course = JSON.parse(this.course);
                const words = JSON.parse(this.words);
                const courseData = {
                    name: this.$refs.courseNameInput.value,
                };
                const data = {
                    words: [],
                    course_id: course.id,
                };

                axios
                    .patch(`/api/courses/${course.id}`, courseData)
                    .then((res) => {
                        words.forEach((item) =>
                            axios
                                .delete(`/api/words/${item.id}`)
                                .then((res) => console.log(res))
                                .catch((error) => console.log(error))
                        );
                    })
                    .then((res) => {
                        for (let i = 0; i < this.polishWords.length; i++) {
                            data.words.push({
                                polish: this.polishWords[i],
                                english: this.englishWords[i],
                            });
                        }
                        axios
                            .post(`/api/words`, data)
                            .then((res) => console.log(res))
                            .catch((error) => console.log(error));
                    })
                    .catch((error) => console.log(error))
                    .then(() => (window.location.href = "/courses"));
            }
        },
        validate() {
            if (this.$refs.courseNameInput.value === "") {
                this.error = true;
                this.errorTitle = "Course Name Error";
                this.errorMessage = "Course Name input is empty";
            }
            this.polishWords.forEach((word) => {
                if (word === "") {
                    this.error = true;
                    this.errorTitle = "Words Error";
                    this.errorMessage = "Fill All Inputs";
                }
            });
            this.englishWords.forEach((word) => {
                if (word === "") {
                    this.error = true;
                    this.errorTitle = "Words Error";
                    this.errorMessage = "Fill All Inputs";
                }
            });
        },
        closeModal() {
            this.error = false;
        },
    },
    created() {
        const wordsArray = JSON.parse(this.words);
        if (wordsArray.length > 0)
            wordsArray.forEach((word) => {
                const polishWordInput = "";
                this.polishWordsInputs.push(polishWordInput);
                this.englishWordsInputs.push(polishWordInput);

                this.polishWords.push(word.polish);
                this.englishWords.push(word.english);
            });
    },
    mounted() {
        const wordsArray = JSON.parse(this.words);
        const course = JSON.parse(this.course);

        if (this.$refs.polishWordsInputs !== undefined) {
            this.$refs.polishWordsInputs.forEach(
                (input, i) => (input.$el.value = this.polishWords[i])
            );
            this.$refs.englishWordsInputs.forEach(
                (input, i) => (input.$el.value = this.englishWords[i])
            );
        }
        this.$refs.wordsQuantityInput.value = wordsArray.length;
        this.$refs.courseNameInput.value = course.name;
    },
};
</script>
