<template>
    <label class="block border rounded py-3 px-4" :class="classes" :for="id" @click="select">
        <input type="radio" class="hidden" :name="name" :id="id" :value="value">
        <p class="">{{ title }}</p>
        <slot></slot>
    </label>
</template>

<script>
export default {
    props: ['name', 'value', 'checked', 'title', 'selected'],
    data () {
        return {
            //
        };
    },
    computed: {
        classes () {
            return this.checked ? [`border-${this.selected}`, `bg-${this.selected}-lightest`] : '';
        },
        id () {
            return [this.name, this.value].join('_');
        },
    },
    methods: {
        select () {
            this.$emit('click', this.value);
        },
    },
}
</script>
