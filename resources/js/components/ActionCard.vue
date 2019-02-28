<template>
    <div class="card-padding mb-4">
        <div class="card">
            <div class="card-header" v-text="title"></div>
            <div class="card-body">
                <p v-html="body"></p>
                <p v-html="created.calendar()"></p>
            </div>
            <div class="card-footer flex">
                <form
                    class="flex flex-grow"
                    v-if="action"
                    :action="action.url"
                    :method="action.method ? 'POST' : 'GET'"
                    >
                    <input
                        type="hidden"
                        name="_token"
                        :value="csrf"
                        >
                    <input
                        v-if="action.method"
                        type="hidden"
                        name="_method"
                        :value="action.method"
                        >
                    <input
                        v-if="action.fields"
                        v-for="field in action.fields"
                        type="hidden"
                        :name="field.name"
                        :value="field.value"
                        >
                    <button
                        class="flex flex-grow w-full text-center justify-center button button-blue rounded-t-none"
                        :class="{'rounded-r-none': action}"
                        v-text="action.label"
                        >
                    </button>
                </form>
                <form
                    class="flex flex-grow"
                    v-if="cancel"
                    :action="cancel.url"
                    :method="cancel.method ? 'POST' : 'GET'"
                    >
                    <input
                        type="hidden"
                        name="_token"
                        :value="csrf"
                        >
                    <input
                        v-if="cancel.method"
                        type="hidden"
                        name="_method"
                        :value="cancel.method"
                        >
                    <input
                        v-if="cancel.fields"
                        v-for="field in cancel.fields"
                        type="hidden"
                        :name="field.name"
                        :value="field.value"
                        >
                    <button
                        class="flex flex-grow w-full text-center justify-center button button-red button-secondary rounded-t-none"
                        :class="{'rounded-l-none': action}"
                        v-text="cancel.label"
                        >
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['title', 'body', 'created_at', 'action', 'cancel'],
    data () {
        return {
            created: moment(this.created_at),
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        };
    },
    methods: {
        //
    },
}
</script>
