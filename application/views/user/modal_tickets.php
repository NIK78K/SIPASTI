<div id="ticketModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[9999]">

    <div class="bg-[#0b1220] border border-gray-800 rounded-xl w-full max-w-2xl p-6">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">
                Tiket Pengguna: <span id="ticketUserName"></span>
            </h3>

            <button onclick="closeTicketModal()"
                    class="text-gray-400 hover:text-white text-xl">
                ✕
            </button>
        </div>

        <div id="ticketContent" class="space-y-3 max-h-[400px] overflow-y-auto">
            <p class="text-gray-400 text-sm">Memuat data...</p>
        </div>

    </div>
</div>
