<template>
    <div class="controls disable-select">
        <div class="overview-controls">
            <div :class="['question-button', currentIndex+1 == index ? 'selected-question' : '']" v-for="index in indexMax" :key="index" @click.prevent="$emit('goTo', index-1)">
                {{ index }}
            </div>
        </div>
        <button class="submit-button" @click.prevent="$emit('submit')" :disabled="!hasAnsweredAll || !answerSelected">Terminer</button>
    </div>
</template>

<script>
export default {
    props: {
        currentIndex: Number,
        indexMax: Number,
        hasAnsweredAll: Boolean,
        answerSelected: Boolean,
    },

     data() {
         return {
         }
     },

    computed: {

    },

    methods: {

    }
}
</script>

<style lang="scss" scoped>
    .controls {
        display: flex;
        justify-content: center;
        align-items: center;

        background-color: rgb(21, 118, 216);
        height: 75px;
        margin: 0;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .overview-controls {
        display: flex;
        align-items: center;
        font-size: 30px;
        height: 100%;
    }


    .question-button {
        display: flex;
        align-items: center;
        font-weight: bold;
        padding: 0 20px;
        background-color: rgb(21, 118, 216);
        height: 100%;
        border-width: 0;
        font-size: 20px;
    }

    .question-button:hover {
        background-color: rgb(16, 90, 165);
        cursor: pointer;
    }

    /* .question-button:last-child {
        border-radius: 0 40px 40px 0;
    }

    .question-button:first-child {
        border-radius: 40px 0 0 40px;
    } */

    .selected-question, .selected-question:hover {
        background-color: rgb(241, 183, 34);
    }

    .button-controls {
        display: flex;
    }

    .flow-controls {
        display: flex;
        justify-content: flex-start;
    }

    .submit-button {
        background-color: green;
        position: fixed;
        bottom: 0;
        right: 0;
        height: 75px;
        width: 200px;
        border-width: 0;
        color: white;
        font-size: 40px;
    }

    .submit-button:hover {
        background-color: rgb(0, 95, 0);
    }

    .submit-button:disabled {
        background-color: rgb(80, 80, 80);
    }

    .disable-select {
        user-select:none;
    }
</style>
