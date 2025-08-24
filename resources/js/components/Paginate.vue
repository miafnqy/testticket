<template>
    <div class="flex">
        <div v-for="(link, index) in links" class="px-1 py-5 mx-auto">
            <router-link
                v-html="link.label" :to="link.url ? link.url : '/users'"
                :class="{'font-bold': link.active}">
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        links: {
            type: Array,
            required: true
        }
    },
    created() {

    }
}
</script>
