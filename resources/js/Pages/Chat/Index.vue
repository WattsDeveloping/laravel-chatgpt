<script setup>
import ChatContent from "@/Components/ChatContent.vue";
import ChatLayout from "@/Layouts/ChatLayout.vue";
import Skeleton from "@/Components/Skeleton.vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const showDeleteButton = ref(false);
const chatContainer = ref(null);
const promtInput = ref(null);

const props = defineProps({
    messages: {
        type: Array,
        required: true
    },
    chat: {
        type: [ null, Object ],
        required: false
    },
});

const form = useForm({
    promt: "",
});

const submit = () => {
    const url = props.chat ? `/chat/${props.chat?.id}` : "/chat";
    form.post(url, {
        onFinish: () => {
            form.reset();
            promtInput.value.focus();
            scrollToBottom();
        },
    });
};

const scrollToBottom = () => {
    if (props.chat) {
        const el = chatContainer.value;
        el.scrollTop = el.scrollHeight;
    }
};
</script>
<template>
    <Head title="Laravel GPT" />
    <ChatLayout>
        <template #aside>
            <ul class="p-2">
                <li class="flex justify-between px-4 py-2 my-2 font-semibold text-green-400 duration-200 rounded-lg bg-slate-900 hover:bg-slate-700">
                    <Link href="/chat" class="w-full">Start a new chat</Link>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </li>
                <template v-for="message in messages" :key="message.id">
                    <li :class="[ message.id === chat?.id ? 'bg-slate-700' : 'bg-slate-900',
                            'px-4 py-2 my-2 flex justify-between font-semibold text-slate-400 hover:bg-slate-700 rounded-lg duration-200',
                        ]">
                        <Link :href="`/chat/${message.id}`">
                            {{ message.context[0].content }}
                        </Link>
                        <div v-if="message.id === chat?.id">
                            <button v-if="!showDeleteButton" @click="showDeleteButton = !showDeleteButton">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-6 h-6 text-red-300">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </button>
                            <span v-if="showDeleteButton" class="flex justify-between">
                                <Link :href="route('chat.destroy', chat?.id)" method="DELETE" as="button" class="text-red-300 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                </Link>
                                <button @click="showDeleteButton = false" class="text-slate-300 hover:text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        </div>
                    </li>
                </template>
            </ul>
        </template>
        <div class="flex w-full text-white">
            <template v-if="chat">
                <div class="flex w-full h-screen bg-slate-900">
                    <div class="w-full overflow-auto pb-36" ref="chatContainer">
                        <template v-for="(content, index) in chat?.context" :key="index">
                            <ChatContent :content="content" />
                        </template>
                        <Skeleton v-show="form.processing" />
                    </div>
                </div>
            </template>
        </div>
        <template #form>
            <section class="top-0 px-6">
                <div class="w-full">
                    <div class="relative flex items-center flex-1">
                        <input type="text"
                            class="w-full text-white rounded-lg bg-slate-700"
                            placeholder="Ask Laravel GPT a question"
                            v-model="form.promt"
                            @keyup.enter="submit"
                            :disabled="form.processing"
                            ref="promtInput"/>
                        <div class="absolute inset-y-0 right-0 flex items-center pl-3">
                            <svg v-if="!form.processing"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-6 h-6 -ml-8 text-slate-200">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            <div class="-ml-10 dot-typing" v-if="form.processing"></div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </ChatLayout>
</template>