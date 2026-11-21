<!-- Modal Edit Task (partial) -->
<div id="modal-edit" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Edit Tugas ✏️</h2>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="edit-form" method="POST" class="space-y-4" action="{{ isset($task) ? route('tasks.update', $task->id) : '' }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Judul <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-title" name="title" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea id="edit-description" name="description" rows="3" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select id="edit-category" name="category_id" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->icon ?? '' }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
                        <select id="edit-priority" name="priority" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Low">🟢 Low</option>
                            <option value="Medium">🟡 Medium</option>
                            <option value="High">🔴 High</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select id="edit-status" name="status" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Inbox">Inbox</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tenggat</label>
                        <input type="date" id="edit-due_date" name="due_date" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-lg shadow-blue-200">
                Simpan Perubahan 🚀
            </button>
        </form>
    </div>
</div>
