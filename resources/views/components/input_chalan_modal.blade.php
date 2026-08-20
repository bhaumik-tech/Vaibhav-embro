<!-- Input Chalan Modal (Hidden by default) -->
<div id="inputChalanModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm transition-opacity">
    
    <!-- Modal Content -->
    <div class="bg-white border-marker rounded-3xl p-4 shadow-xl w-[500px] max-w-[95vw] flex flex-col gap-3 relative scale-95 opacity-0 transition-all duration-300 transform origin-center" id="inputChalanModalContent">
        
        <!-- Title -->
        <h2 class="bg-[#e2e8f0] border-marker rounded-xl py-1 text-center font-bold text-marker shadow-sm text-sm w-3/4 mx-auto">
            Input Chalan
        </h2>

        <!-- Form Fields -->
        <div class="flex flex-col gap-2 mt-2">
            <!-- Party Name + GST -->
            <select class="bg-white border-marker rounded-lg px-3 py-1.5 text-xs text-marker font-semibold focus:outline-none focus:ring-1 focus:ring-green-400 text-center w-full appearance-none">
                <option>Party name+add.+ GST(dropdown list)</option>
            </select>

            <!-- Aapdi furm nu nam -->
            <input type="text" value="Aapdi furm nu nam (auto party wise)" class="bg-white border-marker rounded-lg px-3 py-1.5 text-xs text-marker font-semibold focus:outline-none focus:ring-1 focus:ring-green-400 text-center w-full" readonly>

            <!-- Date and Chalan No -->
            <div class="flex gap-4 px-4 mt-1">
                <input type="text" placeholder="date" class="flex-1 bg-white border-marker rounded-lg px-3 py-1.5 text-xs text-marker font-semibold text-center focus:outline-none focus:ring-1 focus:ring-green-400">
                <input type="text" placeholder="chalan no." class="flex-1 bg-white border-marker rounded-lg px-3 py-1.5 text-xs text-marker font-semibold text-center focus:outline-none focus:ring-1 focus:ring-green-400">
            </div>
        </div>

        <!-- Table / Grid -->
        <div class="mt-3 overflow-x-auto">
            <div class="min-w-max grid grid-cols-[30px_repeat(6,minmax(40px,1fr))] gap-1 text-[10px]">
                
                <!-- Headers -->
                @php
                    $modalHeaders = ['Sr', 'chart', 'detail', 'Mtr.', 'note', 'Pcs', 'bundles'];
                @endphp
                @foreach($modalHeaders as $h)
                    <div class="bg-white border-marker rounded-md text-center font-bold text-marker px-0.5 py-1 flex items-center justify-center">{{ $h }}</div>
                @endforeach

                <!-- Row 1 -->
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-center justify-center">1</div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-center justify-center">camric</div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-center justify-center">Pc/B</div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-center justify-center">2.10</div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-center justify-center">Dark</div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-center justify-center">1200</div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-center justify-center">T-D</div>

                <!-- Row 2 -->
                <button class="bg-white border-marker rounded-md text-center font-bold text-marker px-0.5 py-1 flex items-center justify-center hover:bg-green-50 transition-colors">+</button>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex flex-col items-center justify-center leading-tight">
                    <span>camric</span>
                    <span>print</span>
                    <span>jaam</span>
                    <span>....</span>
                </div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex flex-col items-center justify-center leading-tight">
                    <span>Pc/B</span>
                    <span>CxC</span>
                    <span>Surat</span>
                    <span>Amd</span>
                    <span>....</span>
                </div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex flex-col items-center justify-center leading-tight">
                    <span>1.90</span>
                    <span>2.10</span>
                    <span>2.15</span>
                    <span>2.2</span>
                </div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex flex-col items-center justify-center leading-tight">
                    <span>light</span>
                    <span>dark</span>
                    <span>fruit</span>
                    <span>....</span>
                </div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex items-start justify-center pt-2">
                    <div class="border-marker rounded-sm px-2">....</div>
                </div>
                <div class="bg-white border-marker rounded-md text-center font-semibold text-marker px-0.5 py-1 flex flex-col items-center justify-center leading-tight">
                    <span>Top</span>
                    <span>T-D</span>
                    <span>T-B-D</span>
                </div>

            </div>
        </div>

        <!-- Bottom Buttons -->
        <div class="flex justify-end gap-3 mt-4 pr-2">
            <button class="bg-white border-marker rounded-lg px-6 py-1.5 font-bold text-marker text-xs shadow-sm hover:bg-green-50 transition-colors">
                ok
            </button>
            <button onclick="window.closeInputChalanModal()" class="bg-white border-marker rounded-lg px-6 py-1.5 font-bold text-marker text-xs shadow-sm hover:bg-green-50 transition-colors">
                cancle
            </button>
        </div>

    </div>
</div>
