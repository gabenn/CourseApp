<template>
    <div class="flex flex-column">
        <notifications position="bottom left" />
        <div v-if="test">
            <div v-if="!cheat">
                <!--<ModalComponent
                    v-if="error"
                    :error="error"
                    :message="errorMessage"
                    :title="errorTitle"
                    @closeModal="closeModal"
                >
                </ModalComponent>-->
                <div>
                    <h3>Word {{ currentWord }}/{{ wordsArray.length }}</h3>
                    <h5>Max Time: {{maxTime >= 60 
                    ? ((Math.floor(maxTime/60))+" min "+(maxTime%60)+" seconds") 
                    : (maxTime+" seconds")}}</h5>
                </div>
                <div class="flex justify-content-center">
                    <div>
                        <p class="form-control">
                            {{ wordsArray[currentWord - 1].polish }}
                        </p>
                    </div>
                    <div>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="English Word"
                            v-model="currentAnswer"
                            ref='answerInput'
                        />
                    </div>
                </div>
                <div class="flex justify-content-center">
                    <button class="btn btn-primary" @click="confirm">
                        Confirm
                    </button>
                </div>
            </div>
            <!--<ModalComponent
                v-else
                :error="cheat"
                :message="'Cheating detected. Refresh page to start test again'"
                :title="'Cheating'"
                @closeModal="closeModal"
            ></ModalComponent>-->
        </div>
        <div v-else class="flex flex-column justify-content-center">
            <div class="h3 m-3 flex  justify-content-center text-center flex-column ">
                <p v-if='pass' class='text-success'>
                    You Passed The Test
                </p>
                <p v-else class='text-danger'>
                    You Failed The Test <br/> {{this.errorMessage}}
                </p>
                <p>Your Score: {{ this.good }}/{{ this.wordsArray.length }}</p>
                <p>Your Time: {{this.totalTime}}seconds</p>
            </div>
            <div class="h3 m-3 flex justify-content-center">
                <button class="btn btn-success m-3" @click="goBack">
                    Go Back
                </button>
                <button class="btn btn-success m-3" @click="reRun">
                    Re-Run
                </button>
            </div>
            <div class="h3 m-3 flex justify-content-around">
                <ul class="list-group">
                    <li
                        v-for="(answer, i) in answers"
                        :class="
                            wordsArray[i].english === answer
                                ? 'list-group-item border border-success text-success my-2'
                                : 'list-group-item border border-danger text-danger my-2'
                        "
                    >
                        {{ `${wordsArray[i].polish} - ${answer}`}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import ModalComponent from "./ModalComponent";

export default {
    components: {
        ModalComponent,
    },
    props: ["course", "words"],
    data() {
        return {
            currentWord: 1, 
            currentAnswer: '',
            answers: [],
            good: 0,
            wordsArray: [],
            startTime: new Date(),
            totalTime:0,
            maxTime:0,
            error: false, 
            /**
            * error = {
            *  title = '',
            *  message = '',   
            * }
            */
            errorTitle: "",  
            errorMessage: "",  
            test: true, // mało czytelna nazwa zmiennej
            cheat: false, // didCheat
            pass: false, // didPass?
        };
    },
    methods: {
        confirm() {
            this.validate();
            // Zamiast if (!this.error) validate może zwracać true/false - jeśli false to walidacja się nie powiodła
            
            /**
            * const didValidate = this.validate();
            *
            * if (didValidate) { ... }
            */

            if (!this.error) {
                this.currentAnswer = this.currentAnswer.toLowerCase(); 
                this.answers.push(this.currentAnswer);
                if (
                    this.currentAnswer ===
                    this.wordsArray[this.currentWord - 1].english
                ) {
                    this.good++;
                    this.currentAnswer = "";
                    this.$refs.answerInput.focus();
                }
                if (this.currentWord < this.wordsArray.length) {
                    this.currentWord++;
                    this.currentAnswer = "";
                    this.$refs.answerInput.focus();
                } else {
                    this.test = false;

                    // wszystko niżej wrzuciłbym do metody finishTest albo calculateScore, czytając co się dzieje przy potwierdzaniu nie muszę znać detali tego jak wynik jest uzyskiwany; robi się też lekki bałagan
                    this.totalTime = (new Date().getTime() - this.startTime.getTime())/1000
                    const score = this.good/this.currentWord; 
                    const passTime = this.totalTime < this.maxTime;

                    if(score > 0.5 && passTime) {
                        this.pass = true
                    }
                    else {
                        this.pass = false
                        if(score < 0.5 && !passTime) this.errorMessage = 'Score < 50% And Time > Max Test Time' // Nie powinniśmy raczej traktować słabego wyniku jako error; to nie błąd a informacja
                        else if(score < 0.5) this.errorMessage = "Score < 50%"
                        else if(!passTime) this.errorMessage = "Time > Max Test Time"
                    }
                }
            }
        },
        goBack() {
            const course = JSON.parse(this.course);
            window.location.href = `/courses/${course.id}`;
        },
        reRun: () => document.location.reload(),
        validate() {
            // error lepiej jako obiekt
            if (this.currentAnswer === "") {
                this.error = true;
                this.errorTitle = "Answer Input Error";
                this.errorMessage = "Answer input is empty";
                
                this.$notify({text: "Answer input is empty", error: true}); 
                // return false;
            }

            // return true;
        },
        closeModal() {
            this.error = false;
        },
    },
    created() {
        this.wordsArray = JSON.parse(this.words);

        window.addEventListener("blur", (e) => this.cheat = true);

        this.maxTime = this.wordsArray.length * 30;
    },
};
</script>
