<template>
    <div class="flex flex-column">
        <div v-if="test">
            <div v-if="!cheat">
                <ModalComponent
                    v-if="error"
                    :error="error"
                    :message="errorMessage"
                    :title="errorTitle"
                    @closeModal="closeModal"
                >
                </ModalComponent>
                <div>
                    <h3>Word {{ activeWord }}/{{ wordsArray.length }}</h3>
                    <h5>Max Time: {{maxTime >= 60 
                    ? ((Math.floor(maxTime/60))+" min "+(maxTime%60)+" seconds") 
                    : (maxTime+" seconds")}}</h5>
                </div>
                <div class="flex justify-content-center">
                    <div>
                        <p class="form-control">
                            {{ wordsArray[activeWord - 1].polish }}
                        </p>
                    </div>
                    <div>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="English Word"
                            v-model="answer"
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
            <ModalComponent
                v-else
                :error="cheat"
                :message="'Cheating detected. Refresh page to start test again'"
                :title="'Cheating'"
                @closeModal="closeModal"
            ></ModalComponent>
        </div>
        <div v-else class="flex flex-column justify-content-center">
            <div class="h3 m-3 flex justify-content-center text-center flex-column ">
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
            activeWord: 1,
            answer: '',
            answers: [],
            good: 0,
            wordsArray: [],
            startTime: new Date(),
            totalTime,
            maxTime,
            error: false,
            errorTitle: "",
            errorMessage: "",
            test: true,
            cheat: false,
            pass: false,
        };
    },
    methods: {
        confirm() {
            this.validate();
            if (!this.error) {
                this.answer = this.answer.toLowerCase(); 
                this.answers.push(this.answer);
                if (
                    this.answer ===
                    this.wordsArray[this.activeWord - 1].english
                ) {
                    this.good++;
                    this.answer = "";
                    this.$refs.answerInput.focus();
                }
                if (this.activeWord < this.wordsArray.length) {
                    this.activeWord++;
                    this.answer = "";
                    this.$refs.answerInput.focus();
                } else {
                    this.test = false;
                    this.totalTime = (new Date().getTime() - this.startTime.getTime())/1000
                    const score = this.good/this.activeWord; 
                    const passTime = this.totalTime < this.maxTime;
                    if(score > 0.5 && passTime) this.pass = true
                    else {
                        this.pass = false
                        if(score < 0.5 && !passTime) this.errorMessage = 'Score < 50% And Time > Max Test Time'
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
            if (this.answer === "") {
                this.error = true;
                this.errorTitle = "Answer Input Error";
                this.errorMessage = "Answer input is empty";
            }
        },
        closeModal() {
            this.error = false;
        },
    },
    created() {
        this.wordsArray = JSON.parse(this.words);
        const cheaterInterval = window.setInterval(() => {
            if (!document.hasFocus()) {
                this.cheat = true;
                window.clearInterval(cheaterInterval);
            }
        }, 1000);
        
        this.maxTime = this.wordsArray.length * 30;
    },
};
</script>
