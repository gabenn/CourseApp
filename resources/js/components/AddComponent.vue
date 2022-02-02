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
                v-model="name"
            />
        </div>
        <div>
            <label for="wordsQuantityInput">Words Quantity</label>
            <input
                min="1"
                max="20"
                type="number"
                v-model.number="wordsQuantity"
                placeholder="Words Quantity"
                class="form-control"
                @change="changeInputs"
            />
        </div>
        <div id="wordsBox" class="flex flex-row mt-5 mb-5">
            <ol class="flex flex-column">
                <li v-for="word in words">
                    <WordInput
                        v-model="word.polish"
                        v-bind:placeholderText="'Polish Word'"
                    ></WordInput>
                </li>
            </ol>
            <ol class="flex flex-column">
                <li v-for="word in words">
                    <WordInput
                        v-model="word.english"
                        v-bind:placeholderText="'English Word'"
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
            words: [
                {
                    polish: "",
                    english: "",
                },
            ],
            error: false,
            errorTitle: "",
            errorMessage: "",
            wordsQuantity: 1,
            name: "",
        };
    },
    methods: {
        changeInputs() {
            if (this.wordsQuantity > this.words.length) {
                this.addInput();
            } else this.removeInput();
        },
        addInput() {
            this.words.push({
                polish: "",
                english: "",
            });
        },
        removeInput() {
            this.words = this.words.slice(0, this.wordsQuantity);
        },
        validate() {
            if (this.name === "") {
                this.error = true;
                this.errorTitle = "Course Name Error";
                this.errorMessage = "Course Name input is empty";
            }
            if (
                this.words.polish.length === 0 ||
                this.words.polish.length === 0
            ) {
                this.error = true;
                this.errorTitle = "Words Error";
                this.errorMessage = "Create Some Words";
            }
            const polish = this.words.polish;
            const english = this.words.english;
            for (let i = 0; i < polish.length; i++) {
                if (
                    polish[i] === "" ||
                    polish[i] === undefined ||
                    english[i] === "" ||
                    english[i] === undefined
                ) {
                    this.error = true;
                    this.errorTitle = "Words Error";
                    this.errorMessage = "Fill All Inputs";
                }
            }
        },
        submitForm() {
            this.validate();
            // if (!this.error) {
            //     const courseData = {
            //         name: this.name,
            //     };
            //     axios
            //         .post(`/api/courses`, courseData)
            //         .then((res) => {
            //             const data = {
            //                 words: [],
            //                 course_id: 0,
            //             };
            //             for (let i = 0; i < this.wordsQuantity; i++) {
            //                 data.words.push({
            //                     polish: this.words.polish[i],
            //                     english: this.words.english[i],
            //                 });
            //             }
            //             axios
            //                 .post(`/api/words`, data)
            //                 .then((res) => console.log(response))
            //                 .catch((error) => console.log(error.response));
            //         })
            //         .catch((error) => console.log(error))
            //         .then(() => (window.location.href = "/courses"));
            // }
        },
        closeModal() {
            this.error = false;
        },
    },
    computed: {},
    mounted() {
        this.wordsQuantityInput = 1;
    },
};
</script>
