<template>
    <div class="welcome-card">
        <div class="welcome-card-title">
            {{ welcomeCard.title }}
        </div>
        <div class="welcome-card-text">
            {{ welcomeCard.text }}{{ score }}/{{ maxScore }}
        </div>
    </div>
</template>

<script>export default {
    mounted() {
        console.log('Mounted');
    },
    created() {
    },

    props: {
        score: Number,
        maxScore: Number,
    },

    data() {
        return {
            welcomeCard: {
                title: 'Merci d\'avoir participé',
                text: 'Votre score est de ',
            },
        }
    },

    computed: {
    },

    methods: {
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
