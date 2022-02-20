import Vue from 'vue';
import VueRouter from 'vue-router';

import ImportQuestions from './components/ImportQuestions.vue'
import Questionnaire from './components/Questionnaire.vue'
import QuestionnaireResults from './components/QuestionnaireResults.vue'


Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        // { path: '/', component: ImportQuestions},
        {
            path: '/',
            component: Questionnaire,
        },
    ]
});
