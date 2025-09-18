<template>
    <div v-show=show class="fixed inset-0 size-full bg-black/75 flex justify-center items-center" @click="close">
        <div class="rounded-lg bg-white p-9 flex flex-col justify-center items-center w-full max-w-md" @click.stop>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        close() {
            this.$emit('close');
        }
    }
}
</script>
