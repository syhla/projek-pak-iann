@extends('layouts.app')

@section('content')
<div x-data="categoryHandler()" class="p-12 max-w-5xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-4xl font-bold text-[#D4AF37]">Kelola Kategori</h2>
        <button @click="openAdd()"
            class="bg-[#D4AF37] hover:bg-[#e7be3b] text-white font-semibold px-4 py-2 rounded-xl shadow transition">
            + Tambah Kategori
        </button>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-xl shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <table class="w-full text-sm text-gray-700">
            <thead class="bg-[#6B8E23] text-left text-xl uppercase tracking-wide font-bold text-white">
                <tr>
                    <th class="px-6 py-3">Nama Kategori</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <button @click="openEdit(
                                {{ $category->id }},
                                '{{ $category->name }}',
                                '{{ $category->description ?? '' }}'
                            )" class="text-[#6B8E23] hover:underline font-medium">
                                Edit
                            </button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL --}}
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50">
        <div x-show="showModal" x-transition
            class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg space-y-4 border border-[#6B8E23]">
            <h3 class="text-lg font-bold text-[#6B8E23]" x-text="modalTitle"></h3>
            <form :action="formAction" method="POST" enctype="multipart/form-data">
                @csrf
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="name" id="name" x-model="formName"
                        class="w-full border border-[#6B8E23] p-2 rounded-lg focus:ring-[#6B8E23] focus:border-[#6B8E23]" required>
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Kategori</label>
                    <textarea name="description" id="description" x-model="formDescription" rows="4"
                        class="w-full border border-[#6B8E23] p-2 rounded-lg focus:ring-[#6B8E23] focus:border-[#6B8E23]"></textarea>
                </div>

                <div class="space-y-2">
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar Kategori</label>
                    <input type="file" name="image" id="image"
                        class="w-full border border-[#6B8E23] p-2 rounded-lg focus:ring-[#6B8E23] focus:border-[#6B8E23]">
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" @click="closeModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition">Batal</button>
                    <button type="submit"
                        class="bg-[#6B8E23] hover:bg-[#556B2F] text-white px-4 py-2 rounded-lg font-semibold transition"
                        x-text="submitButtonText"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function categoryHandler() {
        return {
            showModal: false,
            modalTitle: '',
            formName: '',
            formDescription: '',
            formAction: '',
            formMethod: 'POST',
            submitButtonText: '',

            openAdd() {
                this.modalTitle = 'Tambah Kategori';
                this.formName = '';
                this.formDescription = '';
                this.formAction = '{{ route("admin.categories.store") }}';
                this.formMethod = 'POST';
                this.submitButtonText = 'Simpan';
                this.showModal = true;
            },

            openEdit(id, name, description = '') {
                this.modalTitle = 'Edit Kategori';
                this.formName = name;
                this.formDescription = description;
                this.formAction = `/admin/categories/${id}`;
                this.formMethod = 'PUT';
                this.submitButtonText = 'Update';
                this.showModal = true;
            },

            closeModal() {
                this.showModal = false;
            }
        };
    }
</script>
@endsection
