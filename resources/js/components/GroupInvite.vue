<template>
    <div>
        <button
            class="button button-blue button-secondary button-xs"
            id="invite"
            @click="openModal"
            v-text="t('groups.invite')"
            >
        </button>
        <portal to="modals">
            <div
                @click="close"
                v-if="showModal"
                class="fixed pin bg-black z-50 flex items-center justify-around"
                >
                <div
                    @click.stop=""
                    v-if="showModal"
                    class="bg-white flex flex-col p-4 rounded-lg w-full lg:w-1/3 z-50"
                    id="invite-modal"
                    >
                    <div class="mb-3 flex items-start justify-between">
                        <p class="leading-normal" v-text="t('groups.invite_text')"></p>
                        <svg-icon
                            name="icon-close"
                            class="cursor-pointer w-6 h-6 flex-no-shrink primary-transparent secondary-grey-dark"
                            @click="close"
                            >
                        </svg-icon>
                    </div>
                    <div class="flex">
                        <input
                            class="border rounded py-2 px-2 rounded-r-none flex flex-grow"
                            name="email"
                            v-model="email"
                            :placeholder="t('users.email')"
                            @keyup.enter="invite"
                            autofocus
                            >
                        <button
                            class="button button-blue py-2 rounded-l-none"
                            @click="invite"
                            v-text="t('groups.invite')"
                            >
                        </button>
                    </div>
                    <p
                        class="text-green-dark mt-3"
                        v-for="message in invite_messages"
                        v-text="message">
                    </p>
                    <p
                        class="text-red-dark mt-3"
                        v-for="message in error_messages"
                        v-text="message">
                    </p>
                </div>
            </div>
        </portal>
    </div>
</template>

<script>
import SvgIcon from 'vue-svgicon';
import '../icons/icon-close';

export default {
    components: {
        SvgIcon,
    },
    props: ['group'],
    data () {
        return {
            showModal: false,
            email: '',
            invite_messages: [],
            error_messages: [],
        };
    },
    created () {
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                this.close();
            }
        }
        document.addEventListener('keydown', escapeHandler);
        this.$once('hook:destroyed', () => {
            document.removeEventListener('keydown', escapeHandler);
        });
    },
    computed: {
        inviteUrl () {
            return `/groups/${this.group.id}/invites`;
        },
    },
    methods: {
        t (key) {
            return this.$options.filters.trans(key);
        },
        close () {
            this.showModal = false;
        },
        invite () {
            axios.post(this.inviteUrl, {
                email: this.email,
            }).then(({data}) => {
                if (data.invited) {
                    this.invite_messages.push(data.message);
                }
            }).catch(({response}) => {
                if (response.data.message) {
                    this.error_messages.push(response.data.message);
                }
            });
        },
        openModal () {
            this.showModal = true;
        },
    },
}
</script>
