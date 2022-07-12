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
            @click="submit"
            :value="status ? 'Add Course' : 'Edit Course'"
            class="btn btn-primary"
        />
    </div>
</template>
<script>
import WordInput from "./WordInputComponent";
import ModalComponent from "./ModalComponent";

const COURSE_STATUS = {
    COURSE_ADD: "ADD",
    COURSE_EDIT: "EDIT",
}

export default {
    components: {
        WordInput,
        ModalComponent,
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
            error: false,
            errorTitle: "",
            errorMessage: "",
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
            // Co jeśli mamy kilka błędów? Obecnie wyświetla się ostatni znaleziony. Pomyślałbym, żeby zmienić modal chociażby na toast notifications (wyskakujące powiadomienia) i na każdy znaleziony błąd wyświetlać toast.
            // Obecnie jeśli mam dwa błędy i poprawię jeden to mi wyskakuje kolejny model który muszę odklikać i poprawić kolejny błąd i tak w kółko; strasznie frustrujący design.
            if (this.name === "") {
                // Wygląda na to, że warto error włożyć w jakiś obiekt z polami { title: "", message: "" } - wtedy zamiast error = true możesz przypisać ten obiekt i sprawdzać czy error nie jest nullowy.
                // Takie zgrupowane informacje lepiej trzymać w obiekcie, nie jako osobne pola.
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
                    return;
                    // tu warto zrobić return; 
                    // jeśli mamy już błąd to nie ma sensu loopować przez kolejne potencjalne setki słów.
                }
            });
        },
        submit() {
            this.validate();
            if (!this.error) {
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
                                .catch((error) => console.log(error.response)); // Warto poinformować użytkowniak o tym, że wystąpił błąd (toast)
                        })
                        .catch((error) => console.log(error)) // Warto poinformować użytkownika o tym, że wystąpił błąd (np. przez toast)
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
                                    .catch((error) => console.log(error)) // użytkownik
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
                                .catch((error) => console.log(error)); // użytkownik
                        })
                        .catch((error) => console.log(error))
                        .then(() => (window.location.href = "/courses"));
                }
            }
        },
        closeModal() {
            this.error = false;
        },
    },
    created() {
        if (this.course !== undefined) {
            this.status = COURSE_STATUS.COURSE_EDIT;
            this.words = [];
            this.name = JSON.parse(this.course).name; // Mało realistyczny scenariusz, ale co jeśli json parse nawali?
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