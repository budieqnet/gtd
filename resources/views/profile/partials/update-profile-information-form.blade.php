<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-white text-2xl overflow-hidden">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="foto" class="w-full h-full object-cover" id="profile-preview">
                    @else
                        <span id="profile-initial">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                <label for="photo" class="absolute -bottom-1 -right-1 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700 transition shadow-md text-xs">
                    <i class="fa-solid fa-camera"></i>
                </label>
                <input type="file" id="photo" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(event)">
            </div>
            <div class="text-sm text-slate-500">
                <p class="font-medium text-slate-700">Foto Profil</p>
                <p>Format: JPG/PNG, max 2MB</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">NIP</label>
                <input type="text" value="{{ $user->nip ?? '-' }}" readonly
                       class="w-full p-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan</label>
                <input type="text" value="{{ $user->positionData?->name ?? $user->position ?? '-' }}" readonly
                       class="w-full p-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Depan</label>
                <input type="text" value="{{ $user->front_title ?? '-' }}" readonly
                       class="w-full p-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Belakang</label>
                <input type="text" value="{{ $user->back_title ?? '-' }}" readonly
                       class="w-full p-2 border border-slate-200 rounded-lg bg-slate-50 text-slate-500 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1" for="name">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-preview');
            const initial = document.getElementById('profile-initial');
            if (preview) {
                preview.src = e.target.result;
            } else {
                const container = document.querySelector('.w-20.h-20');
                if (initial) initial.remove();
                const img = document.createElement('img');
                img.id = 'profile-preview';
                img.src = e.target.result;
                img.className = 'w-full h-full object-cover';
                container.appendChild(img);
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>