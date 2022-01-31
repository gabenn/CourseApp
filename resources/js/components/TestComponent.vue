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
                    <h5>Max Time: {{wordsArray.length*30 >= 60 
                    ? ((Math.floor(wordsArray.length*30/60))+" minutes "+((wordsArray.length*30)%60)+" seconds") 
                    : ((wordsArray.length*30)+" seconds")}}</h5>
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
                            ref="answerInput"
                        />
                    </div>
                </div>
                <div class="flex justify-content-center">
                    <button class="btn btn-primary" @click="confirm">
                        Confirm
                    </button>
                </div>
            </div>
            <div v-else>
                <ModalComponent
                    v-if="cheat"
                    :error="cheat"
                    :message="'Cheating detected. Refresh page to start test again'"
                    :title="'Cheating'"
                    @closeModal="closeModal"
                ></ModalComponent>
            </div>
        </div>
        <div v-else class="flex flex-column justify-content-center">
            <div class="h3 m-3 flex justify-content-center text-center flex-column ">
                <p v-if='pass' class='text-success'>
                    You Passed The Test
                </p>
                <p v-else class='text-danger'>
                    You Failed The Test <br/> {{this.errorMessage}}
                </p>
                <p>Your Score: {{ this.goodAnswers }}/{{ this.wordsArray.length }}</p>
                <p>Your Time: {{this.testTotalTime}}seconds</p>
                

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
                        v-for="(answer, index) in answers"
                        :class="
                            wordsArray[index].english == answer
                                ? 'list-group-item border border-success text-success my-2'
                                : 'list-group-item border border-danger text-danger my-2'
                        "
                    >
                        {{ `${wordsArray[index].polish} - ${answer}`}}
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
            goodAnswers: 0,
            activeWord: 1,
            wordsArray: [],
            answers: [],
            test: true,
            error: false,
            errorTitle: "",
            errorMessage: "",
            cheat: false,
            testStartTime: 0,
            testEndTime: 0,
            testTotalTime:0,
            pass: false,
        };
    },
    methods: {
        confirm() {
            this.validate();
            if (!this.error) {
                this.answers.push(this.$refs.answerInput.value);
                if (
                    this.$refs.answerInput.value ===
                    this.wordsArray[this.activeWord - 1].english
                ) {
                    this.goodAnswers++;
                    this.$refs.answerInput.value = "";
                    this.$refs.answerInput.focus();
                }
                if (this.activeWord < this.wordsArray.length) {
                    this.activeWord++;
                    this.$refs.answerInput.value = "";
                    this.$refs.answerInput.focus();
                } else {
                    this.test = false;
                    this.testEndTime = new Date();
                    this.testTotalTime = (this.testEndTime.getTime() - this.testStartTime.getTime())/1000
                    if(this.goodAnswers/this.activeWord > 0.5 && this.testTotalTime < this.activeWord*30) this.pass = true
                    else {
                        this.pass = false
                        if(this.goodAnswers/this.activeWord < 0.5 && this.testTotalTime > this.activeWord*30) this.errorMessage = 'Score < 50% And Time > Max Test Time'
                        else if(this.goodAnswers/this.activeWord < 0.5) this.errorMessage = "Score < 50%"
                        else if(this.testTotalTime > this.activeWord*30) this.errorMessage = "Time > Max Test Time"
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
            if (this.$refs.answerInput.value === "") {
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
            if (!document.hasFocus() && !this.cheat) {
                this.cheat = true;
                window.clearInterval(cheaterInterval);
            }
        }, 1000);
    },
    mounted(){
        this.testStartTime = new Date();
    },
};
</script>
