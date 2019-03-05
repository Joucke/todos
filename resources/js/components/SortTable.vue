<template>
    <table>
        <tbody>
            <tr v-for="item, index in items" :key="item.id">
                <slot name="row" :item="item" :index="index" :items="items" :up="up" :down="down" :t_count="t_count"></slot>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ['listData', 'sortUrl', 'sortKey'],
    data () {
        return {
            items: this.listData,
        };
    },
    methods: {
        t (key) {
            return this.$options.filters.trans(key);
        },
        t_count (key, count) {
            let lang = this.t(key).split('|');
            if (count == 1) {
                return lang[0].replace(':count', 1);
            }
            return lang[1].replace(':count', count);
        },
        down (index) {
            let items = this.items;
            [items[index], items[index + 1]] = [items[index + 1], items[index]];
            let data = {};
            data[this.sortKey] = {};
            items.forEach((item, index) => {
                data[this.sortKey][item.id] = {sort_order: index + 1};
            });
            axios.patch(this.sortUrl, data)
                .then(({data}) => {
                    this.items = data.items;
                });
        },
        up (index) {
            let items = this.items;
            [items[index], items[index - 1]] = [items[index - 1], items[index]];
            let data = {};
            data[this.sortKey] = {};
            items.forEach((item, index) => {
                data[this.sortKey][item.id] = {sort_order: index + 1};
            });
            axios.patch(this.sortUrl, data)
                .then(({data}) => {
                    this.items = data.items;
                });
        },
    },
}
</script>
