<x-layouts.app title="Categories">
    <x-page-header title="Categories" :breadcrumbs="[['label' => 'Categories', 'url' => '#']]">
        <x-slot name="actions">
            <button @click="$dispatch('open-modal', 'category-create')" class="btn btn-primary">+ New Category</button>
        </x-slot>
    </x-page-header>
    <div class="p-6">
        <x-card class="overflow-hidden">
            @if($categories->isEmpty())
                <x-empty-state title="No categories yet">
                    <x-slot name="actions">
                        <button @click="$dispatch('open-modal', 'category-create')" class="btn btn-primary">Create Category</button>
                    </x-slot>
                </x-empty-state>
            @else
            <table class="w-full text-sm">
                <thead><tr class="bg-[#F9FAFB] border-b border-[#E2E8F0]">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Name</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Slug</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Products</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-[#64748B] font-heading">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($categories as $cat)
                <tr class="table-row-alt border-b border-[#F1F5F9] hover:bg-[#F5F7FA]">
                    <td class="px-5 py-3 font-medium text-[#1A202C]">{{ $cat->name }}</td>
                    <td class="px-5 py-3 text-[#64748B] font-mono text-xs">{{ $cat->slug }}</td>
                    <td class="px-5 py-3 text-center text-[#64748B]">{{ $cat->products_count }}</td>
                    <td class="px-5 py-3 text-center flex justify-center gap-2">
                        <button
                            @click="$dispatch('open-modal', 'category-edit-{{ $cat->id }}')"
                            class="btn btn-ghost btn-sm">Edit</button>
                        <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm text-red-500" {{ $cat->products_count ? 'disabled title=Has products' : '' }}>Delete</button>
                        </form>
                    </td>
                </tr>

                {{-- Inline edit modal per row --}}
                <div x-data x-on:open-modal.window="$event.detail === 'category-edit-{{ $cat->id }}' && ($el.querySelector('dialog').showModal())"
                     x-on:close-modal.window="$el.querySelector('dialog').close()">
                    <dialog class="modal-box rounded-xl p-0 w-full max-w-md backdrop:bg-black/40">
                        <form method="POST" action="{{ route('categories.update', $cat) }}" class="p-6">
                            @csrf @method('PUT')
                            <h3 class="font-semibold font-heading text-[#1A202C] mb-4">Edit Category</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">Name *</label>
                                    <input type="text" name="name" value="{{ $cat->name }}" class="form-input" required>
                                </div>
                                <div>
                                    <label class="form-label">Description</label>
                                    <textarea name="description" rows="2" class="form-textarea">{{ $cat->description }}</textarea>
                                </div>
                            </div>
                            <div class="flex gap-3 mt-5">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" @click="$el.closest('dialog').close()" class="btn btn-secondary">Cancel</button>
                            </div>
                        </form>
                    </dialog>
                </div>
                @endforeach
                </tbody>
            </table>
            @endif
        </x-card>
    </div>

    {{-- Create modal --}}
    <div x-data x-on:open-modal.window="$event.detail === 'category-create' && ($el.querySelector('dialog').showModal())"
         x-on:close-modal.window="$el.querySelector('dialog').close()">
        <dialog class="modal-box rounded-xl p-0 w-full max-w-md backdrop:bg-black/40">
            <form method="POST" action="{{ route('categories.store') }}" class="p-6">
                @csrf
                <h3 class="font-semibold font-heading text-[#1A202C] mb-4">New Category</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="2" class="form-textarea">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-5">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" @click="$el.closest('dialog').close()" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </dialog>
    </div>
</x-layouts.app>
