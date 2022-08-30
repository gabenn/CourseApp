<template>
    <div class="flex flex-column">
        <notifications position="bottom left" />
        <div v-if="test">
            <div v-if="!didCheat">
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
            <div v-else class="h3 m-3 text-center flex justify-content-center flex-column">
                <p class="text-danger">
                    You cheated.
                </p>
                <div class="h3 m-3 flex justify-content-center">
                    <button class="btn btn-success m-3" @click="goBack">
                        Go Back
                    </button>
                    <button class="btn btn-success m-3" @click="reRun">
                        Re-Run
                    </button>
                </div>
            </div>
        </div>
        <div v-else class="flex flex-column justify-content-center">
            <div class="h3 m-3 flex justify-content-center text-center flex-column ">
                <p v-if='didPass' class='text-success'>
                    You Passed The Test
                </p>
                <p v-else class='text-danger'>
                    You Failed The Test <br/> {{this.scoreMessage}}
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
export default {
    components: {
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
            scoreMessage: "",  
            test: true, 
            didCheat: false, 
            didPass: false, 
        };
    },
    methods: {
        confirm() {

            if (this.validate()) {
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
                    this.finishTest(); 
                }
            }
        },
        finishTest() {
            this.totalTime = (new Date().getTime() - this.startTime.getTime())/1000
            const score = this.good/this.currentWord; 
            const passTime = this.totalTime < this.maxTime
            if(score > 0.5 && passTime) {
                this.didPass = true
            }
            else {
                this.didPass = false
                if(score < 0.5 && !passTime) this.scoreMessage = 'Score < 50% And Time > Max Test Time'
                else if(score < 0.5) this.scoreMessage = "Score < 50%"
                else if(!passTime) this.scoreMessage = "Time > Max Test Time"
            }

        },
        goBack() {
            const course = JSON.parse(this.course);
            window.location.href = `/courses/${course.id}`;
        },
        reRun: () => document.location.reload(),
        validate() {
            if (this.currentAnswer === "") {                
                this.$notify({text: "Answer input is empty", duration: 4000}); 
                return false;
            }

            return true;
        },
    },
    created() {
        this.wordsArray = JSON.parse(this.words);

        window.addEventListener("blur", (e) => this.didCheat = true);

        this.maxTime = this.wordsArray.length * 30;
    },
};
</script>
