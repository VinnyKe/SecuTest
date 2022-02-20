<template>
    <div class="welcome-card">
        <div class="welcome-card-title">
            {{ welcomeCard.title }}
        </div>
        <!-- <div class="welcome-card-text">
            {{ welcomeCard.text }}
        </div> -->
        <div
            class="button"
            @click="startQuestionnaire"
        >
            Commencer
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
    },

    created() {
    },

    props: {
    },

    data() {
        return {
            welcomeCard: {
                title: 'Questionnaire SécuKite',
                text: 'Ce questionnaire a pour but d\'évaluer vos connaissances sur la sécurité en kitesurf. Il est constitué de 20 questions.',
            }
        }
    },

    computed: {
    },

    methods: {
        startQuestionnaire() {
            this.$emit('startQuestionnaire');
        }
    },
}
</script>

<style lang="scss" scoped>
.welcome-card {
    display: flex;
    flex-direction: column;
    align-items: center;

    position: absolute;
    top: 40%;
    left: 0;

    width: 100%;

    .welcome-card-title {
        font-weight: bold;
        font-size: 25px;
        margin-bottom: 10px;
    }

    .welcome-card-text {
        font-size: 20px;
    }
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
