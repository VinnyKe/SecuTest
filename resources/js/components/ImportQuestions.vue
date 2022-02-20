<template>
    <div class="admin-panel">
        <h2 class="title">
            Questions
        </h2>
        <div class="actions">
            <div class="button" @click="importQuestions">Importer</div>
            <div class="button" @click="resetAll">Supprimer tout</div>
        </div>
    </div>
</template>

<script>
export default {
    created() {
    },

    data() {
        return {
            questions: {},
        }
    },

    methods: {
        importQuestions() {
            axios.post('api/questions/import');
        },
        resetAll() {
            axios.post('api/questions/reset');
        }
    }
}
</script>

<style lang="scss" scoped>
.admin-panel {
    display: flex;
    flex-direction: column;
    align-items: center;

    .title {
        width: 100%;
        text-align: center;
    }

    .actions {
        display: flex;
    }

    .button {
        margin: 10px 5px;
    }
}
</style>
