<template>
    <div>
        <notifications position="bottom left" />
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
            @click="submit"
            :value="status ? 'Add Course' : 'Edit Course'"
            class="btn btn-primary"
        />
    </div>
</template>
<script>
import WordInput from "./WordInputComponent";

const COURSE_STATUS = {
    COURSE_ADD: "ADD",
    COURSE_EDIT: "EDIT",
}

export default {
    components: {
        WordInput,
    },
    props: ["course", "wordsProps"],
    data() {
        return {
            words: [
                {
                    polish: "",
                    english: "",
                },
            ],
            name: "",
            status: COURSE_STATUS.COURSE_ADD,
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
            this.words = this.words.splice(0, value);
            if (this.words.length === 0) {
                this.addInput();
            }
        },
        validate() {
            let validated = true
            if (this.name === "") {
                validated = false;
                this.$notify({text: "Course Name input is empty", duration: 4000}); 
            }
            if (this.words.length === 0) {
                validated = false;
                this.$notify({text: "Create Some Words", duration: 4000}); 
            }
            this.words.forEach((word) => {
                if (word.polish === "" || word.english === "") {
                    validated = false;
                    this.$notify({text: "Fill All Inputs", duration: 4000}); 
                    return validated;  //tu coś się buguje potrafi się pojawić parę razy
                }
            });
            return validated;
        },
        submit() {
            if (this.validate()) {
                const courseData = {
                    name: this.name,
                };
                const data = {
                    words: [],
                    course_id: 0,
                };
                if (this.status === COURSE_STATUS.COURSE_ADD) {
                    const courseData = {
                        name: this.name,
                    };
                    axios
                        .post(`/api/courses`, courseData)
                        .then((res) => {
                            this.words.forEach((word) => {
                                data.words.push({
                                    polish: word.polish,
                                    english: word.english,
                                });
                            });
                            axios
                                .post(`/api/words`, data)
                                .then((res) => console.log(response))
                                .catch((error) => this.$notify({text: error})) 
                        })
                        .catch((error) => this.$notify({text: error})) 
                        .then(() => (window.location.href = "/courses"));
                } else {
                    const course = JSON.parse(this.course);
                    const words = JSON.parse(this.wordsProps);
                    data.course_id = course.id;

                    axios
                        .patch(`/api/courses/${course.id}`, courseData)
                        .then((res) => {
                            words.forEach((item) =>
                                axios
                                    .delete(`/api/words/${item.id}`)
                                    .then((res) => console.log(res))
                                    .catch((error) => this.$notify({text: error})) 
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
                                .catch((error) => this.$notify({text: error})); 
                        })
                        .catch((error) => this.$notify({text: error}))
                        .then(() => (window.location.href = "/courses"));
                }
            }
        },
    },
    created() {
        if (this.course !== undefined) {
            this.status = COURSE_STATUS.COURSE_EDIT;
            this.words = [];
            this.name = JSON.parse(this.course).name; 
            JSON.parse(this.wordsProps).forEach((word) => {
                this.words.push({
                    polish: word.polish,
                    english: word.english,
                });
            });
        } else {
            this.status = COURSE_STATUS.COURSE_ADD;
        }
    },
};
</script>