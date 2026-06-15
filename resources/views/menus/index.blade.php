@extends('layouts.dashboard')

@section('content')
<div x-data="{
    showCreateMenuModal: false,
    showAddLinkModal: false,
    showEditLinkModal: false,
    showEditMenuModal: false,
    editLinkData: { id: '', label: '', url: '', order: 0 },
    editMenuData: { id: '', name: '', location: '' }
}">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Navigation Menus</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Design your site navigation structure</p>
        </div>
        <button @click="showCreateMenuModal = true" class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Menu
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center px-4 py-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg">
            <svg class="mr-2 h-4 w-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar: Manage Menus -->
        <div class="w-full md:w-1/3 lg:w-1/4">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Manage Menus</h3>
                </div>
                <div class="p-2 space-y-1">
                    @forelse($menus as $menu)
                        <a href="{{ route('menus.index', ['menu' => $menu->id]) }}"
                           class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition duration-150 {{ (isset($activeMenu) && $activeMenu->id === $menu->id) ? 'bg-slate-100 dark:bg-slate-700/80 text-blue-700 dark:text-blue-400 font-medium' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                            <div class="flex items-center gap-2 truncate">
                                <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <span class="truncate">{{ $menu->name }}</span>
                            </div>
                            <span class="text-[10px] uppercase text-slate-400">{{ $menu->location }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 p-3 text-center">No menus created yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Content: Active Menu Links -->
        <div class="w-full md:w-2/3 lg:w-3/4">
            @if($activeMenu)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                Menu: {{ $activeMenu->name }}
                                @if(auth()->user()->hasRole('admin') || auth()->id() === $activeMenu->created_by)
                                    <button @click="editMenuData = { id: '{{ $activeMenu->id }}', name: '{{ addslashes($activeMenu->name) }}', location: '{{ addslashes($activeMenu->location) }}' }; showEditMenuModal = true" class="text-slate-400 hover:text-blue-600 transition" title="Edit Menu">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                @endif
                            </h3>
                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-4">
                                <span>Location: <span class="text-blue-600 dark:text-blue-400">{{ $activeMenu->location ?? 'None' }}</span></span>
                                <span>Created By: <span class="font-medium">{{ $activeMenu->author->name ?? 'Unknown' }}</span></span>
                            </div>
                        </div>
                        
                        @if(auth()->user()->hasRole('admin') || auth()->id() === $activeMenu->created_by)
                            <div class="flex items-center gap-2">
                                <button @click="showAddLinkModal = true" class="inline-flex items-center px-3 py-2 bg-slate-800 dark:bg-slate-700 hover:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Link
                                </button>
                                <form action="{{ route('menus.destroy', $activeMenu) }}" method="POST" onsubmit="return confirm('Delete this entire menu group?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition" title="Delete Menu">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @forelse($activeMenu->items as $item)
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-sm transition">
                                <div class="flex items-start gap-3 overflow-hidden">
                                    <div class="mt-1 text-slate-400">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                    <div class="truncate">
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ $item->label }}</p>
                                        <a href="{{ $item->url }}" target="_blank" class="text-xs text-blue-600 dark:text-blue-400 hover:underline truncate block mt-0.5">{{ $item->url }}</a>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 ml-4 shrink-0">
                                    @if(auth()->user()->hasRole('admin') || auth()->id() === $activeMenu->created_by)
                                        <button @click="editLinkData = { id: '{{ $item->id }}', label: '{{ addslashes($item->label) }}', url: '{{ addslashes($item->url) }}', order: {{ $item->order }} }; showEditLinkModal = true" class="p-1.5 text-slate-400 hover:text-blue-600 transition" title="Edit Link">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('menus.items.destroy', ['menu' => $activeMenu->id, 'item' => $item->id]) }}" method="POST" onsubmit="return confirm('Delete this link?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 transition" title="Delete Link">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">View only</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl">
                                <p class="text-sm text-slate-500">No links added to this menu yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-12 text-center flex flex-col items-center justify-center min-h-[300px]">
                    <svg class="h-12 w-12 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-800 dark:text-slate-200">No Menu Selected</h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-sm">Select a menu from the left panel or create a new one to start adding links.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    
    <!-- Create Menu Modal -->
    <div x-show="showCreateMenuModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showCreateMenuModal" @click="showCreateMenuModal = false" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm"></div>
            <div x-show="showCreateMenuModal" class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 shadow-xl rounded-2xl">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Create New Menu Group</h3>
                <form action="{{ route('menus.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Menu Name</label>
                            <input type="text" name="name" required placeholder="e.g., Main Navigation" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Location (Optional)</label>
                            <input type="text" name="location" placeholder="e.g., Header, Footer" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showCreateMenuModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">Create Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($activeMenu)
    <!-- Edit Menu Modal -->
    <div x-show="showEditMenuModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showEditMenuModal" @click="showEditMenuModal = false" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm"></div>
            <div x-show="showEditMenuModal" class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 shadow-xl rounded-2xl">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Edit Menu Group</h3>
                <form :action="`/menus/${editMenuData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Menu Name</label>
                            <input type="text" name="name" x-model="editMenuData.name" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Location</label>
                            <input type="text" name="location" x-model="editMenuData.location" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showEditMenuModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Link Modal -->
    <div x-show="showAddLinkModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showAddLinkModal" @click="showAddLinkModal = false" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm"></div>
            <div x-show="showAddLinkModal" class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 shadow-xl rounded-2xl">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Add New Link</h3>
                <form action="{{ route('menus.items.store', $activeMenu->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Label</label>
                            <input type="text" name="label" required placeholder="e.g., About Us" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">URL</label>
                            <input type="text" name="url" placeholder="e.g., /about" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Order Position</label>
                            <input type="number" name="order" value="0" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showAddLinkModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-lg transition">Add Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Link Modal -->
    <div x-show="showEditLinkModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showEditLinkModal" @click="showEditLinkModal = false" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm"></div>
            <div x-show="showEditLinkModal" class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-800 shadow-xl rounded-2xl">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Edit Link</h3>
                <form :action="`/menus/{{ $activeMenu->id }}/items/${editLinkData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Label</label>
                            <input type="text" name="label" x-model="editLinkData.label" required class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">URL</label>
                            <input type="text" name="url" x-model="editLinkData.url" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Order Position</label>
                            <input type="number" name="order" x-model="editLinkData.order" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showEditLinkModal = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-lg transition">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
