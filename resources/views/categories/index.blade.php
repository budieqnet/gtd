@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-slate-800">🏷️ Categories</h1>
        <button onclick="document.getElementById('modal-cat').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition">
            <i class="fa-solid fa-plus mr-2"></i> New Category
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between group">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                    <span class="font-bold text-slate-700">{{ $category->name }}</span>
                </div>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition">
                    @csrf @method('DELETE')
                    <button class="text-slate-400 hover:text-red-500 transition"><i class="fa-solid fa-trash"></i></button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Add Category -->
<div id="modal-cat" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Add Category 🎨</h2>
            <button onclick="document.getElementById('modal-cat').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Category Name</label>
                <input type="text" name="name" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Color</label>
                <input type="color" name="color" class="w-full h-10 p-1 border rounded-lg cursor-pointer outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition shadow-lg shadow-indigo-200">
                Save Category! 🚀
            </button>
        </form>
    </div>
</div>
@endsection