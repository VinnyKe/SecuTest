<template>
    <div class="container">
        <div class="question-content" v-bind="currentQuestion" v-if="!isFetching">
            <h2 class="question-number">Question {{ currentIndex+1 }}</h2>
            <img class="question-image" :src="currentQuestion.media.path" v-if="!!currentQuestion.media">
            <div class="question-text">{{ currentQuestion.text }}</div>
            <div class="answers-container disable-select">
                <a
                    v-for="(answer, index) in currentQuestion.answers"
                    :class="[
                        'answer',
                        answer.id === selectedAnswerId ?'selected-answer' : '',
                    ]"

                    :key="answer.id"
                    @click.prevent="answerClicked(answer.id)"
                >
                    <div class="answer-prefix">
                        {{ prefixes[index] }}
                    </div>
                    <div class="answer-text">
                        {{ answer.text }}
                    </div>
                </a>
            </div>
            <div v-if="canSaveAnswer"
                class="button validate-button"
                @click.prevent="saveAnswer"
            >
                Valider
            </div>
            <div style="height: 75px"/>
            <questionnaire-navbar
                :current-index="currentIndex"
                :index-max="questions.length"
                :has-answered-all="hasAnsweredAll"
                :answer-selected="!!selectedAnswerId"
                @goTo="goToQuestion"
                @submit="submitQuestionnaire"
            />
        </div>
    </div>
</template>

<script>export default {
    created() {
        axios.get('api/questions').then((response) => {
            this.questions = response.data;
            this.isFetching = false;

            this.questions.forEach((question, index) => {
                //Suffle question's answers
                question.answers.length > 2 ? question.answers = this.shuffle(question.answers): null;
                //Give index to question
                question.index = index;
            });
            if (this.SKIP_ANSWERS) {
                this.questions.forEach((question, index) => {
                    this.results[index] = {
                        'question_id':question.id,
                        'answer_id':question.answers[0].id
                    };
                });
                this.selectedAnswerId = this.questions[0].answers[0].id;
            }
            this.isSettingUp = false;
        });
    },

    props: {
    },

    data() {
        return {
            //CONFIG
            SKIP_ANSWERS: true,

            //VARIABLES
            prefixes:  ['A','B','C','D'],
            currentIndex: 0,
            isFetching: true,
            isSettingUp: true,
            questions: {},
            results: {},
            selectedAnswerId: null,
        }
    },

    computed: {
        canSaveAnswer() {
            return !!this.selectedAnswerId && !this.answerIsSaved;
        },

        answerIsSaved() {
            if (!!this.results[this.currentIndex]) {
                return this.results[this.currentIndex].answer_id == this.selectedAnswerId;
            }  else {
                return false;
            }
        },

        currentQuestion() {
            return this.questions[this.currentIndex];
        },

        hasNext() {
            return this.currentIndex < this.questions.length-1;
        },

        hasPrevious() {
            return this.currentIndex > 0;
        },

        hasAnsweredAll() {
            return Object.keys(this.results).length == this.questions.length;
        }
    },

    methods: {
        answerClicked(answerId) {
            if (this.selectedAnswerId != answerId) {
                this.selectedAnswerId = answerId;
            }
        },

        saveAnswer() {
            //Save selected answer in results
            this.results[this.currentIndex] = {
                'question_id':this.currentQuestion.id,
                'answer_id':this.selectedAnswerId
            };
            //Go to next question if there is one
            this.hasNext ? this.goToQuestion(this.currentIndex+1): this.goToQuestion(this.currentIndex);
        },

        getNext() {
            if (this.hasNext) {
                this.goToQuestion(this.currentIndex+1);
            }
        },

        getPrevious() {
            if (this.hasPrevious) {
                this.goToQuestion(this.currentIndex-1);
            }
        },

        shuffle(a) {
            var j, x, i;
            for (i = a.length - 1; i > 0; i--) {
                j = Math.floor(Math.random() * (i + 1));
                x = a[i];
                a[i] = a[j];
                a[j] = x;
            }
            return a;
        },

        goToQuestion(index) {
            console.log('Going to question '+(index+1));
            this.currentIndex = index;
            this.selectedAnswerId = !!this.results[this.currentIndex] ? this.results[this.currentIndex].answer_id : null;
        },

        submitQuestionnaire() {
            axios.post('api/questionnaire-runs', this.results).then(response => {
                this.$router.push({name:'results', params: {questionnaire_id: response.data }});
            });
        },
    },
}
</script>

<style lang="scss" scoped>

    .question-content {
        display: flex;
        flex-direction: column;
        padding: 0;
        max-width: 1200px;
        margin: 0 auto;

        .validate-button {
            background-color: green;

            font-size: 30px;
            width: 140px;
            height: 40px;

        }
        .validate-button:hover {
            background-color: rgb(0, 95, 0);
        }
    }

    .question-image {
        margin-top: 20px;
        width: auto;
        height: auto;
    }

    .question-number {
        padding-top: 20px;
        margin: 0;
    }

    .question-text {
        margin-top: 10px;
        font-size: 30px;
        text-align: left;
        width: 100%;
    }

    .answers-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin: 20px 0;
    }

    .answer {
        flex-basis: calc(50% - 40px);
        display: flex;
        align-self: center;

        margin: 20px 0;
        padding: 0 10px;
        text-decoration: none;
        color: black;
        font-size: 25px;
        border-radius: 20px;
        border: 3px solid transparent;

        background-color: lightskyblue;
    }
    .answer:hover {
        cursor: pointer;
        color: black;
        background-color: goldenrod;
    }
    .selected-answer {
        background-color: rgb(241, 183, 34);
        border: 3px solid rgb(122, 88, 0);
    }

    .answer-text {
        width: 100%;
        margin: auto 0;
    }

    .answer-prefix {
        font-weight: bold;
        font-size: 28px;
        margin: auto 15px;
    }

    .myvars {
        color: rgb(201, 241, 255);
        color: rgb(5, 185, 252);
        color: rgb(250, 221, 6);
    }
</style>
