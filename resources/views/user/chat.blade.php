@extends('layouts.app')
@section('title', 'گفتگوی گروهی')

@section('content')

<div class="mb-5 flex items-center justify-between">
    <h1 class="text-2xl font-black font-heading text-white flex items-center gap-3">
        <span class="material-symbols-outlined text-3xl" style="color:#4D9FFF;">forum</span>
        گفتگوی گروهی
    </h1>
    <span class="text-xs px-3 py-1 rounded-full font-bold flex items-center gap-1.5"
          style="background:rgba(77,159,255,0.1);color:#4D9FFF;border:1px solid rgba(77,159,255,0.2);" id="online-badge">
        <span class="w-2 h-2 rounded-full animate-pulse" style="background:#4D9FFF;"></span>
        آنلاین
    </span>
</div>

<div class="glass-card rounded-3xl overflow-hidden animate-slide-up" x-data="chatApp()" x-init="init()">

    {{-- Chat area --}}
    <div id="chat-messages" class="overflow-y-auto px-5 py-5 space-y-3" style="height:calc(100vh - 300px);min-height:400px;max-height:600px;">
        @foreach($messages as $msg)
        @php $isMe = $msg->user_id === auth()->id(); @endphp
        <div class="flex items-start gap-3 {{ $isMe ? 'flex-row-reverse' : '' }}" data-msg-id="{{ $msg->id }}">
            {{-- Avatar --}}
            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black flex-shrink-0"
                 style="{{ $isMe ? 'background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;' : 'background:rgba(255,255,255,0.1);color:#F0F4FF;' }}">
                {{ mb_strtoupper(mb_substr($msg->user->name, 0, 1, 'UTF-8')) }}
            </div>
            {{-- Bubble --}}
            <div class="{{ $isMe ? 'items-end' : 'items-start' }} flex flex-col" style="max-width:70%;">
                <span class="text-[10px] mb-1 font-semibold {{ $isMe ? 'ml-1' : 'mr-1' }}" style="color:{{ $isMe ? '#00e476' : 'rgba(185,203,185,0.5)' }};">
                    {{ $isMe ? 'شما' : $msg->user->name }}
                    @if($msg->user->username)
                        <span style="color:rgba(185,203,185,0.3);">·</span>
                        <span style="color:rgba(185,203,185,0.3);">{{ '@' . $msg->user->username }}</span>
                    @endif
                </span>
                <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed break-words"
                     style="{{ $isMe ? 'background:rgba(0,228,118,0.12);color:#e0f5e9;border:1px solid rgba(0,228,118,0.2);border-top-left-radius:4px;' : 'background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.9);border:1px solid rgba(255,255,255,0.08);border-top-right-radius:4px;' }}">
                    {{ $msg->body }}
                </div>
                <span class="text-[10px] mt-1 {{ $isMe ? 'ml-1' : 'mr-1' }}" style="color:rgba(185,203,185,0.3);">{{ $msg->created_at->diffForHumans() }}</span>
            </div>
            @if(auth()->user()->is_admin || $msg->user_id === auth()->id())
            <button @click="deleteMsg({{ $msg->id }})" class="opacity-0 group-hover:opacity-100 mt-1 transition-opacity cursor-pointer"
                    style="color:rgba(255,90,90,0.5);"
                    onmouseover="this.style.color='#ff5a5a'" onmouseout="this.style.color='rgba(255,90,90,0.5)'">
                <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
            </button>
            @endif
        </div>
        @endforeach

        {{-- New messages will be appended here by JS --}}
        <div id="new-messages-anchor"></div>
    </div>

    {{-- Input area --}}
    <div class="px-5 py-4" style="border-top:1px solid rgba(255,255,255,0.07);">
        <form @submit.prevent="sendMessage()" class="flex gap-3">
            <input type="text" x-model="newMsg" @keydown.enter.prevent="sendMessage()"
                   placeholder="پیام خود را بنویسید..."
                   class="stitch-input flex-1"
                   style="padding:10px 16px;"
                   maxlength="500"
                   :disabled="sending">
            <button type="submit"
                    :disabled="!newMsg.trim() || sending"
                    class="px-5 py-2.5 rounded-xl font-bold text-sm cursor-pointer transition-all"
                    style="background:#00e476;color:#003919;"
                    :style="(!newMsg.trim() || sending) ? 'opacity:0.5;cursor:not-allowed;' : ''">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1,'wght' 700;">send</span>
            </button>
        </form>
        <p class="text-[10px] mt-2 text-center" style="color:rgba(185,203,185,0.2);">پیام‌ها هر ۳ ثانیه بروز می‌شوند • حداکثر ۵۰۰ کاراکتر</p>
    </div>

</div>

@endsection

@push('scripts')
<script>
const CHAT_ME_ID = {{ auth()->id() }};
const IS_ADMIN = {{ auth()->user()->is_admin ? 'true' : 'false' }};
let lastId = {{ $messages->max('id') ?? 0 }};

function chatApp() {
    return {
        newMsg: '',
        sending: false,

        init() {
            this.scrollToBottom();
            setInterval(() => this.poll(), 3000);
        },

        scrollToBottom() {
            const el = document.getElementById('chat-messages');
            if (el) el.scrollTop = el.scrollHeight;
        },

        async poll() {
            try {
                const resp = await fetch(`{{ route('chat.messages') }}?since=${lastId}`, {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                const data = await resp.json();
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(m => {
                        this.appendMessage(m);
                        lastId = Math.max(lastId, m.id);
                    });
                    this.scrollToBottom();
                }
            } catch(e) {}
        },

        appendMessage(m) {
            const anchor = document.getElementById('new-messages-anchor');
            const div = document.createElement('div');
            div.className = `flex items-start gap-3 ${m.is_me ? 'flex-row-reverse' : ''}`;
            div.dataset.msgId = m.id;
            div.innerHTML = `
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black flex-shrink-0"
                     style="${m.is_me ? 'background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;' : 'background:rgba(255,255,255,0.1);color:#F0F4FF;'}">
                    ${m.avatar}
                </div>
                <div class="${m.is_me ? 'items-end' : 'items-start'} flex flex-col" style="max-width:70%;">
                    <span class="text-[10px] mb-1 font-semibold ${m.is_me ? 'ml-1' : 'mr-1'}"
                          style="color:${m.is_me ? '#00e476' : 'rgba(185,203,185,0.5)'};">
                        ${m.is_me ? 'شما' : m.user_name}
                    </span>
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed break-words"
                         style="${m.is_me ? 'background:rgba(0,228,118,0.12);color:#e0f5e9;border:1px solid rgba(0,228,118,0.2);border-top-left-radius:4px;' : 'background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.9);border:1px solid rgba(255,255,255,0.08);border-top-right-radius:4px;'}">
                        ${this.escHtml(m.body)}
                    </div>
                    <span class="text-[10px] mt-1" style="color:rgba(185,203,185,0.3);">${m.created_at}</span>
                </div>
                ${(IS_ADMIN || m.is_me) ? `<button onclick="deleteMsgGlobal(${m.id})" class="mt-1" style="color:rgba(255,90,90,0.5);background:none;border:none;cursor:pointer;"><span class="material-symbols-outlined" style="font-size:14px;">delete</span></button>` : ''}
            `;
            anchor.before(div);
        },

        async sendMessage() {
            if (!this.newMsg.trim() || this.sending) return;
            this.sending = true;
            try {
                const resp = await fetch('{{ route('chat.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({body: this.newMsg}),
                });
                const data = await resp.json();
                if (data.message) {
                    this.appendMessage(data.message);
                    lastId = Math.max(lastId, data.message.id);
                    this.newMsg = '';
                    this.scrollToBottom();
                }
            } catch(e) {}
            this.sending = false;
        },

        deleteMsg(id) {
            deleteMsgGlobal(id);
        },

        escHtml(s) {
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }
    };
}

async function deleteMsgGlobal(id) {
    if (!confirm('این پیام حذف شود؟')) return;
    await fetch(`/chat/messages/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        }
    });
    const el = document.querySelector(`[data-msg-id="${id}"]`);
    if (el) el.remove();
}
</script>
@endpush
