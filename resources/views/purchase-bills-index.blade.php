@extends('layouts.app')
@section('title', 'Purchase Bills')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-4 shrink-0 px-4 pt-4">
        <a href="{{ url('/') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Purchase Bills Management
        </div>
        @canpage('purchase_bill', 'edit')
<a href="{{ route('purchase-bill.create') }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700">
            + Add New Entry
        </a>
@endcanpage
    </div>

    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm shrink-0 mb-4 mx-4">
        <div class="flex flex-1 gap-2 overflow-x-auto custom-scrollbar pb-1">

            @forelse($firms as $firm)
                <a href="{{ request()->url() }}?firm_id={{ $firm->id }}" class="px-6 py-2 font-bold shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider border border-slate-200 {{ request('firm_id') == $firm->id ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                    {{ $firm->name }}
                </a>
            @empty
                <span class="text-slate-400 text-sm font-bold uppercase tracking-widest px-4 py-2">No Firms Added</span>
            @endforelse
        </div>
    </div>

    @if(session('success'))
        <div class="mx-4 mb-4 bg-green-100 border border-green-500 text-green-700 px-4 py-3 font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="mx-4 mb-4 bg-white border border-slate-300 shadow-sm flex-1 overflow-hidden flex flex-col">
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300">
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300 w-16 text-center">#</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Bill Date</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Bill No</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Firm</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Payee</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Amount</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest text-center w-40">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseBills as $bill)
                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="p-4 text-sm font-semibold text-slate-500 border-r border-slate-200 text-center">{{ $loop->iteration }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-200">{{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}</td>
                            <td class="p-4 text-sm font-bold text-slate-700 border-r border-slate-200">{{ $bill->bill_no ?: '-' }}</td>
                            <td class="p-4 text-sm font-bold text-indigo-700 border-r border-slate-200">{{ $bill->firm->name ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-teal-700 border-r border-slate-200">{{ $bill->company_name ?: '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-200">Rs {{ number_format($bill->amount, 2) }}</td>
                            <td class="p-4 flex items-center justify-center gap-3">
                                <a href="{{ route('purchase-bill.show', $bill) }}" class="text-blue-500 hover:text-blue-700 transition-colors" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                @if($bill->image)
                                <button type="button" onclick="openImageModal('{{ asset('storage/' . $bill->image) }}')" class="text-teal-600 hover:text-teal-800 transition-colors" title="View Image">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </button>
                                @endif
                                @canpage('purchase_bill', 'edit')
<a href="{{ route('purchase-bill.edit', $bill) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
@endcanpage
                                @canpage('purchase_bill', 'remove')
<form action="{{ route('purchase-bill.destroy', $bill) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this bill?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
@endcanpage
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                No Purchase Bills Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Image Viewer Modal -->
<div id="imageModal" class="fixed inset-0 z-[100] hidden bg-slate-900/90 backdrop-blur-sm flex flex-col p-4 sm:p-10 transition-opacity">
    <!-- Modal Header (Controls) -->
    <div class="flex items-center justify-between mb-4 bg-white p-3 rounded-lg shadow-lg shrink-0">
        <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 ml-2">Receipt Image Viewer</h3>
        <div class="flex items-center gap-2">
            <button type="button" onclick="zoomOut()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition-colors shadow-sm border border-slate-300" title="Zoom Out">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
            </button>
            <span id="zoomLevel" class="text-xs font-bold text-slate-500 w-12 text-center">100%</span>
            <button type="button" onclick="zoomIn()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition-colors shadow-sm border border-slate-300" title="Zoom In">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
            </button>
            <div class="w-px h-6 bg-slate-300 mx-1"></div>
            <button type="button" onclick="resetZoom()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded transition-colors shadow-sm border border-slate-300" title="Reset Zoom">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
            <button type="button" onclick="closeImageModal()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded transition-colors shadow-sm border border-slate-300 ml-2" title="Close Viewer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Modal Body (Image Container) -->
    <div class="flex-1 overflow-auto bg-slate-900/50 rounded-lg shadow-inner flex items-center justify-center p-8 border border-white/10" id="imageContainer">
        <img id="modalImage" src="" alt="Receipt" class="max-w-full max-h-full object-contain shadow-2xl transition-transform duration-200 ease-out origin-center bg-white" style="transform: scale(1);">
    </div>
</div>

<script>
    let currentZoom = 1;
    const ZOOM_STEP = 0.25;
    const MAX_ZOOM = 4;
    const MIN_ZOOM = 0.25;

    function openImageModal(url) {
        document.getElementById('modalImage').src = url;
        document.getElementById('imageModal').classList.remove('hidden');
        resetZoom();
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function updateZoom() {
        document.getElementById('modalImage').style.transform = `scale(${currentZoom})`;
        document.getElementById('zoomLevel').innerText = Math.round(currentZoom * 100) + '%';
        
        // Adjust the container styling based on zoom level to allow scrolling if image gets too big
        if(currentZoom > 1) {
            document.getElementById('modalImage').classList.remove('max-w-full', 'max-h-full', 'object-contain');
        } else {
            document.getElementById('modalImage').classList.add('max-w-full', 'max-h-full', 'object-contain');
        }
    }

    function zoomIn() {
        if (currentZoom < MAX_ZOOM) {
            currentZoom += ZOOM_STEP;
            updateZoom();
        }
    }

    function zoomOut() {
        if (currentZoom > MIN_ZOOM) {
            currentZoom -= ZOOM_STEP;
            updateZoom();
        }
    }

    function resetZoom() {
        currentZoom = 1;
        updateZoom();
    }
    
    // Allow closing by clicking outside the image
    document.getElementById('imageContainer').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });
</script>
@endsection
