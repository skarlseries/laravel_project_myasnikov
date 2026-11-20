<template>
    <div
        v-if="article"
        class="article-notification"
    >
        <div class="notification-header">
            <strong>Новая статья!</strong>

            <button @click="article = null">
                ×
            </button>
        </div>

        <p>На сайте добавлена новая статья:</p>

        <a :href="article.url">
            {{ article.name }}
        </a>
    </div>
</template>

<script>
export default {
    data() {
        return {
            article: null
        };
    },

    mounted() {
        console.log('Vue-компонент уведомлений запущен');

        if (!window.Echo) {
            console.error('Laravel Echo не подключён');
            return;
        }

        window.Echo
        .channel('articles')
        .listen('.NewArticleEvent', (event) => {
            console.log('Получено уведомление:', event);
            this.article = event.article;
        });
    }
};
</script>

<style scoped>
.article-notification {
    position: fixed;
    top: 25px;
    right: 25px;
    z-index: 9999;
    width: 340px;
    padding: 20px;
    background: white;
    border: 1px solid #dce3f2;
    border-radius: 14px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.15);
}

.notification-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    color: #3158d5;
}

.notification-header button {
    border: none;
    background: transparent;
    font-size: 22px;
    cursor: pointer;
}

.article-notification p {
    color: #687386;
}

.article-notification a {
    color: #3158d5;
    font-weight: 600;
    text-decoration: none;
}
</style>