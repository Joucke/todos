<template>
    <label class="block border rounded py-3 px-4 cursor-pointer relative" :class="classes" :for="id">
        <input type="radio" class="opacity-0 absolute pointer-events-none" :name="name" :id="id" :value="value" @input="selecting">
        <p class="">{{ title }}</p>
        <slot></slot>
    </label>
</template>

<script>
export default {
    props: ['name', 'value', 'checked', 'title', 'selected'],
    computed: {
        classes () {
            return this.checked ? [`border-${this.selected}`, `bg-${this.selected}-lightest`] : '';
        },
        id () {
            return [this.name, this.value].join('_');
        },
    },
    methods: {
        selecting () {
            this.$emit('selecting', this.value);
        },
    },
}
</script>
