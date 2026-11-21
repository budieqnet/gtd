@php
    $title = 'Pesan';
@endphp

@extends('layouts.app')

@section('content')
<style>
#messenger { height: calc(100vh - 140px); min-height: 500px; }
.msg-sidebar { width: 320px; flex-shrink: 0; }
.msg-search input { padding-left: 2.5rem; }
.msg-search i { left: 1rem; }
.conv-item.active { background: #dbeafe; }
.conv-item.unread { font-weight: 600; }
.conv-item.unread .conv-last { font-weight: 600; }
.msg-bubble { max-width: 75%; padding: 10px 14px; border-radius: 18px; word-wrap: break-word; }
.msg-bubble.mine { background: #3b82f6; color: white; border-bottom-right-radius: 4px; }
.msg-bubble.other { background: #f1f5f9; color: #1e293b; border-bottom-left-radius: 4px; }
.msg-attachment { display: inline-block; margin-top: 6px; }
.msg-attachment img, .msg-attachment video { max-width: 240px; max-height: 200px; border-radius: 12px; cursor: pointer; }
.msg-time { font-size: 10px; margin-top: 4px; opacity: 0.7; }
.msg-input { border-top: 1px solid #e2e8f0; }
.msg-input textarea { resize: none; max-height: 120px; }
.file-preview { position: relative; display: inline-block; }
.file-preview .remove { position: absolute; top: -8px; right: -8px; width: 22px; height: 22px; background: #ef4444; color: white; border-radius: 50%; font-size: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
.avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b; flex-shrink: 0; }
.avatar-sm { width: 36px; height: 36px; font-size: 13px; }
.empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #94a3b8; }
.empty-state i { font-size: 64px; margin-bottom: 16px; }
</style>

<div id="messenger" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex" x-data="messengerApp()">
    <div class="msg-sidebar border-r border-slate-200 flex flex-col">
        <div class="p-3 border-b border-slate-200">
            <div class="relative msg-search">
                <i class="fa-solid fa-search absolute top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Cari pegawai..." class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" x-model="searchQuery">
            </div>
        </div>
        <div class="flex-1 overflow-y-auto">
            <div x-show="searchQuery.length === 0">
                <template x-for="conv in conversations" :key="conv.id">
                    <div class="conv-item flex items-center gap-3 px-4 py-3 cursor-pointer border-b border-slate-50 hover:bg-slate-50 transition"
                         :class="{ 'active': activeConv === conv.id, 'unread': conv.unread > 0 }"
                         @click="openConversation(conv)">
                        <div class="avatar" x-show="!conv.photo" x-text="conv.name.charAt(0)"></div>
                        <img x-show="conv.photo" :src="conv.photo" class="avatar">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-slate-800 truncate" x-text="conv.name"></span>
                                <span class="text-xs text-slate-400" x-text="conv.last_time"></span>
                            </div>
                            <div class="flex justify-between items-center mt-0.5">
                                <span class="text-xs text-slate-500 truncate conv-last" x-text="conv.last_message || 'Mulai percakapan'"></span>
                                <span x-show="conv.unread > 0" class="ml-2 w-5 h-5 flex items-center justify-center bg-blue-600 text-white text-xs font-bold rounded-full" x-text="conv.unread"></span>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="conversations.length === 0" class="py-8 text-center text-sm text-slate-400">
                    Belum ada percakapan
                </div>
                <div class="border-t border-slate-200 mt-2">
                    <p class="px-4 pt-3 pb-1 text-xs font-semibold uppercase tracking-wider text-slate-400">Semua Pegawai</p>
                    <template x-for="emp in employees" :key="emp.id">
                        <div class="flex items-center gap-3 px-4 py-2.5 cursor-pointer border-b border-slate-50 hover:bg-slate-50 transition"
                             @click="startConversation(emp.id)">
                            <div class="relative shrink-0">
                                <div class="avatar avatar-sm" x-text="emp.name.charAt(0)"></div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white" :class="emp.is_online ? 'bg-green-500' : 'bg-slate-300'"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-slate-700 truncate" x-text="emp.name"></div>
                                <div class="text-xs" :class="emp.is_online ? 'text-green-600' : 'text-slate-400'" x-text="emp.is_online ? 'Online' : 'Offline'"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div x-show="searchQuery.length > 0">
                <template x-for="emp in filteredEmployees" :key="emp.id">
                    <div class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-slate-50 transition border-b border-slate-50"
                         @click="startConversation(emp.id)">
                        <div class="relative shrink-0">
                            <div class="avatar avatar-sm" x-text="emp.name.charAt(0)"></div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white" :class="emp.is_online ? 'bg-green-500' : 'bg-slate-300'"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-slate-700 truncate" x-text="emp.name"></div>
                            <div class="text-xs" :class="emp.is_online ? 'text-green-600' : 'text-slate-400'" x-text="emp.is_online ? 'Online' : 'Offline'"></div>
                        </div>
                    </div>
                </template>
                <div x-show="filteredEmployees.length === 0" class="py-8 text-center text-sm text-slate-400">
                    Pegawai tidak ditemukan
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <template x-if="!activeConv">
            <div class="empty-state">
                <i class="fa-regular fa-comments text-slate-300"></i>
                <p class="text-lg font-medium text-slate-400">Pilih percakapan untuk mulai chatting</p>
            </div>
        </template>
        <template x-if="activeConv">
            <div class="flex flex-col h-full">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center gap-3 bg-white shrink-0">
                    <div class="avatar avatar-sm" x-show="!activeConvData.photo" x-text="activeConvData.name.charAt(0)"></div>
                    <img x-show="activeConvData.photo" :src="activeConvData.photo" class="avatar avatar-sm">
                    <span class="font-semibold text-slate-800 text-sm" x-text="activeConvData.name"></span>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-3" id="msg-container" x-ref="msgContainer">
                    <template x-for="msg in messages" :key="msg.id">
                        <div class="flex" :class="msg.is_mine ? 'justify-end' : 'justify-start'">
                            <div :class="'msg-bubble ' + (msg.is_mine ? 'mine' : 'other')">
                                <div x-show="msg.message" class="text-sm" x-text="msg.message"></div>
                                <template x-for="att in msg.attachments" :key="att.id">
                                    <div class="msg-attachment">
                                        <template x-if="att.type.startsWith('image/')">
                                            <img :src="att.url" @click="window.open(att.url)">
                                        </template>
                                        <template x-if="att.type.startsWith('video/')">
                                            <video :src="att.url" controls></video>
                                        </template>
                                        <template x-if="!att.type.startsWith('image/') && !att.type.startsWith('video/')">
                                            <a :href="att.url" target="_blank" class="flex items-center gap-2 text-sm bg-white/20 rounded-lg px-3 py-2">
                                                <i class="fa-solid fa-file"></i>
                                                <span x-text="att.name"></span>
                                            </a>
                                        </template>
                                    </div>
                                </template>
                                <div class="msg-time" x-text="msg.created_at"></div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="msg-input p-3 shrink-0">
                    <template x-if="files.length > 0">
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(f, idx) in files" :key="idx">
                                <div class="file-preview">
                                    <template x-if="f.type.startsWith('image/')">
                                        <img :src="f.url" class="h-16 w-16 object-cover rounded-lg border">
                                    </template>
                                    <template x-if="!f.type.startsWith('image/')">
                                        <div class="h-16 w-16 flex items-center justify-center bg-slate-100 rounded-lg border text-xs text-slate-500">
                                            <i class="fa-solid fa-paperclip text-lg"></i>
                                        </div>
                                    </template>
                                    <div class="remove" @click="files.splice(idx, 1)">&times;</div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <div class="flex items-end gap-2">
                        <label class="p-2 text-slate-400 hover:text-blue-600 cursor-pointer transition">
                            <i class="fa-solid fa-paperclip text-lg"></i>
                            <input type="file" multiple accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" class="hidden" @change="handleFiles($event)">
                        </label>
                        <textarea rows="1" placeholder="Ketik pesan..." class="flex-1 px-4 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" x-model="newMessage" @keydown.enter.prevent="sendMessage"></textarea>
                        <button @click="sendMessage" class="p-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition" :disabled="!newMessage && files.length === 0">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function messengerApp() {
    return {
        conversations: [],
        messages: [],
        employees: @json($employees),
        searchQuery: '',
        activeConv: null,
        activeConvData: {},
        newMessage: '',
        files: [],
        pollTimer: null,
        msgTick: 0,

        get filteredEmployees() {
            var q = this.searchQuery.toLowerCase();
            return this.employees.filter(function(e) {
                return e.name.toLowerCase().includes(q) || (e.nip && e.nip.toLowerCase().includes(q));
            });
        },

        init() {
            this.fetchConversations();
            this.fetchEmployees();
            this.pollTimer = setInterval(() => {
                this.fetchConversations();
                this.fetchEmployees();
            }, 15000);
        },

        fetchEmployees() {
            fetch('{{ url('/messenger/employees') }}')
                .then(r => r.json())
                .then(data => {
                    this.employees = data;
                });
        },

        fetchConversations() {
            fetch('{{ url('/messenger/conversations') }}')
                .then(r => r.json())
                .then(data => {
                    this.conversations = data;
                });
        },

        openConversation(conv) {
            this.activeConv = conv.id;
            this.activeConvData = conv;
            this.searchQuery = '';
            this.fetchMessages();
        },

        fetchMessages() {
            if (!this.activeConv) return;
            fetch('{{ url('/messenger/conversations') }}/' + this.activeConv + '/messages')
                .then(r => r.json())
                .then(data => {
                    this.messages = data;
                    this.$nextTick(() => {
                        var c = this.$refs.msgContainer;
                        if (c) c.scrollTop = c.scrollHeight;
                    });
                    if (this.pollTimer) clearInterval(this.pollTimer);
                    this.pollTimer = setInterval(() => {
                        this.fetchMessages();
                        this.msgTick++;
                        // Refresh status online tiap ~15 detik selama membuka percakapan
                        if (this.msgTick % 5 === 0) this.fetchEmployees();
                    }, 3000);
                });
        },

        startConversation(empId) {
            fetch('{{ url('/messenger/start') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ employee_id: empId })
            })
                .then(r => r.json())
                .then(data => {
                    var conv = this.conversations.find(c => c.id === data.conversation_id);
                    if (conv) {
                        this.openConversation(conv);
                    } else {
                        this.fetchConversations();
                        setTimeout(() => {
                            conv = this.conversations.find(c => c.id === data.conversation_id);
                            if (conv) this.openConversation(conv);
                        }, 500);
                    }
                });
        },

        handleFiles(event) {
            var self = this;
            Array.from(event.target.files).forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    self.files.push({ file: file, url: e.target.result, type: file.type, name: file.name });
                };
                reader.readAsDataURL(file);
            });
            event.target.value = '';
        },

        sendMessage() {
            if (!this.newMessage && this.files.length === 0) return;
            var self = this;
            var formData = new FormData();
            if (this.newMessage) formData.append('message', this.newMessage);
            this.files.forEach(function(f) { formData.append('attachments[]', f.file); });
            fetch('{{ url('/messenger/conversations') }}/' + this.activeConv + '/send', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: formData
            })
                .then(r => r.json())
                .then(function() {
                    self.newMessage = '';
                    self.files = [];
                    self.fetchMessages();
                    self.fetchConversations();
                });
        }
    }
}
</script>
@endsection
