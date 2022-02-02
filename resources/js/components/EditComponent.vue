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
                :value="words.length"
                placeholder="Words Quantity"
                class="form-control"
                @change="changeInputs"
            />
        </div>
        <div id="wordsBox" class="flex flex-row mt-5 mb-5">
            <ol class="flex flex-column px-0">
                <li
                    v-for="word in words"
                    class="my-2 flex justify-content-center"
                >
                    <WordInput
                        v-model="word.polish"
                        v-bind:placeholderText="'Polish Word'"
                    ></WordInput>
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
    props: ["course", "wordsprops"],
    data() {
        return {
            words: [],
            error: false,
            errorTitle: "",
            errorMessage: "",
            name: "",
        };
    },
    methods: {
        changeInputs(event) {
            if (event.target.value > this.words.length) {
                this.addInput();
            } else {
                this.removeInput(event.target.value);
            }
        },
        addInput() {
            this.words.push({
                polish: "",
                english: "",
            });
        },
        removeInput(value) {
            this.words = this.words.slice(0, value);
            if (this.words.length === 0) {
                this.addInput();
            }
        },
        validate() {
            if (this.name === "") {
                this.error = true;
                this.errorTitle = "Course Name Error";
                this.errorMessage = "Course Name input is empty";
            }
            if (this.words.length === 0) {
                this.error = true;
                this.errorTitle = "Words Error";
                this.errorMessage = "Create Some Words";
            }
            this.words.forEach((word) => {
                if (word.polish === "" || word.english === "") {
                    this.error = true;
                    this.errorTitle = "Words Error";
                    this.errorMessage = "Fill All Inputs";
                }
            });
        },
        submitForm() {
            this.validate();
            if (!this.error) {
                const course = JSON.parse(this.course);
                const words = JSON.parse(this.wordsprops);
                const courseData = {
                    name: this.name,
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
                        this.words.forEach((word) => {
                            data.words.push({
                                polish: word.polish,
                                english: word.english,
                            });
                        });
                        axios
                            .post(`/api/words`, data)
                            .then((res) => console.log(res))
                            .catch((error) => console.log(error));
                    })
                    .catch((error) => console.log(error))
                    .then(() => (window.location.href = "/courses"));
            }
        },
        closeModal() {
            this.error = false;
        },
    },
    created() {
        this.name = JSON.parse(this.course).name;
        JSON.parse(this.wordsprops).forEach((word) => {
            this.words.push({
                polish: word.polish,
                english: word.english,
            });
        });
    },
};
</script>
