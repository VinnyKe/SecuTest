<template>
    <div>
        <QuestionnaireWelcome
            v-if="start"
            @startQuestionnaire="startQuestionnaire()"
        />
        <QuestionnaireResults
            v-if="end"
            :score="score"
            :max-score="getMaxScore()"
        />
        <div class="questionnaire-wrapper" v-if="!isFetching && !start && !end">
            <img class="image"
                v-if="currentQuestion.media.length > 0"
                :src="this.currentQuestion.media[0].original_url"
            >
            <h3 class="question">
                {{ currentQuestion.text }}
            </h3>
            <div
                class="answers-wrapper"
                v-if="!!selectedAnswers"
            >
                <div
                    v-for="answer in currentQuestion.answers"
                    :key="answer.id"
                    :class="[
                        answerIsSelected(answer.id) ? 'selected-answer' : '',
                        'answer',
                    ]"
                    @click="answerClicked(answer.id)"
                >
                    {{ answer.text}}
                    </div>
            </div>
            <div
                :class="[
                    'button',
                    hasSelectedAnswer ? 'validate-button' : 'button-disabled'
                ]"
                @click="hasSelectedAnswer ? nextQuestion : null"
            >
                {{ (currentQuestion.id == questions[questions.length-1].id) ? 'Terminer' :  'Suivant' }}
            </div>
        </div>
    </div>
</template>

<script>
import QuestionnaireWelcome from './QuestionnaireWelcome.vue';
import QuestionnaireResults from './QuestionnaireResults.vue';
export default {
    components: { QuestionnaireWelcome, QuestionnaireResults },
    /**
     * TODO
     *  - get answers with slectedAnswers object then add them to userAnswers when saved (no logic with userAnswers)
     */
    mounted() {
        axios.get('api/get-questionnaire').then((response) => {
            this.questions = response.data;
            this.isFetching = false;

            // TESTING
            if (this.testing) {
                console.log('Testing mode active');
                this.currentIndex = 19;
                this.questions.forEach(element => {
                    this.userAnswers[element.id] = {};
                    this.userAnswers[element.id].answers = [];
                    this.userAnswers[element.id].answers.push(element.answers[0].id);
                    this.userAnswers[element.id].isCorrect = !!element.answers[0].isCorrect;

                    if (this.questions[this.currentIndex].id == element.id) {
                        this.selectedAnswers.push(element.answers[0].id);
                    }
                });
            }
        });
    },
    created() {
    },

    props: {
    },

    data() {
        return {
            // DEBUG
            testing: false,

            //VARIABLES
            start: false,
            end: false,
            isFetching: true,
            questions: {},
            userAnswers: {},
            selectedAnswers: [],
            currentIndex: 0,
            score: 0,
        }
    },

    computed: {
        currentQuestion() {
            return this.questions[this.currentIndex];
        },
        hasSelectedAnswer() {
            return this.selectedAnswers.length > 0;
        }
    },

    methods: {
        answerIsSelected(answerId) {
            return this.selectedAnswers.includes(answerId);
        },
        answerClicked(answerId) {
            if (!this.selectedAnswers.includes(answerId)) {
                this.selectedAnswers.push(answerId);
            } else {
                this.removeFromArray(this.selectedAnswers, answerId);
            }
        },
        saveAnswers() {
            this.userAnswers[this.currentQuestion.id] = {};
            this.userAnswers[this.currentQuestion.id].isCorrect = true;
            this.userAnswers[this.currentQuestion.id].answers = this.selectedAnswers;

            // Check if question answered correctly
            for (const questionAnswer of this.currentQuestion.answers) {
                if (this.selectedAnswers.includes(questionAnswer.id)) {
                    if (!questionAnswer.isCorrect) {
                        this.userAnswers[this.currentQuestion.id].isCorrect = false;
                        break;
                    }
                }else if(!!questionAnswer.isCorrect) {
                    // If user missed a correct answer than question is considered incorrect
                    this.userAnswers[this.currentQuestion.id].isCorrect = false;
                    break;
                }
            }
        },
        removeFromArray(array, element) {
            var index = array.indexOf(element);
            if (index > -1) {
                array.splice(index, 1);
            }
        },
        nextQuestion() {
            // Save selected answers
            this.saveAnswers();

            // Check if we are on last question
            if (this.currentQuestion.id == this.questions[this.questions.length-1].id) {
                // If so, end the questionnaire
                this.endQuestionnaire();
            } else {
                // Else go to next question
                this.currentIndex++;
            }
            this.selectedAnswers = [];
        },
        startQuestionnaire() {
            this.start = false;
        },
        endQuestionnaire() {
            for (const [questionId, userAnswer] of Object.entries(this.userAnswers)) {
                this.score += !!userAnswer.isCorrect ? 1 : 0;
                console.log('Question '+questionId+' is correct: '+userAnswer.isCorrect);
            }
            this.end = true;
        },
        getMaxScore() {
            return this.questions.length;
        },
    },
}
</script>

<style lang="scss" scoped>
.questionnaire-wrapper {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    max-width: 100%;
}
.image {
    margin-bottom: 20px;
    width: 100%;
    max-width: 600px;
    height: auto;
}
.question {
    text-align: center;
    margin-top: 10px;
    padding: 0 10px;
}
.answers-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin: 20px 0;
}
.answer {
    flex-basis: calc(50% - 30px);
    display: flex;
    justify-content: center;
    align-items: center;
    align-self: center;
    text-align: center;

    max-width: 800px;
    min-width: 320px;

    margin: 10px;
    padding: 0 10px;
    font-size: 25px;
    border-radius: 20px;
    border: 3px solid transparent;

    background-color: lightskyblue;
}
.answer:hover {
    background-color: rgb(90, 186, 247);
    cursor: pointer;
}
.selected-answer, .selected-answer:hover {
    background-color: rgb(233, 207, 14);
}
.myvars {
    //Default
    color: rgb(201, 241, 255);
    color: rgb(5, 185, 252);
    color: rgb(250, 221, 6);

    //Dark
    color: rgb(201, 241, 255);
    color: rgb(3, 158, 214);
    color: rgb(233, 207, 14);
}
</style>
