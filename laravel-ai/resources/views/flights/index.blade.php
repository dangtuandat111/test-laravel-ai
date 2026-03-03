<div class="p-6 max-w-lg mx-auto bg-white rounded-xl shadow-md space-y-4">
    <div id="chat-content" class="h-64 overflow-y-auto border-b p-2 text-sm">
        <p class="text-gray-400">Hệ thống: Tôi có thể giúp gì cho hành trình của bạn?</p>
    </div>

    <div class="flex gap-2">
        <input type="text" id="user-query" class="border p-2 w-full rounded" placeholder="Nhập yêu cầu...">
        <button onclick="askAI()" class="bg-indigo-600 text-white px-4 py-2 rounded">Tìm</button>
    </div>
</div>

<script>
    async function askAI() {
        const query = document.getElementById('user-query').value;
        const chatContent = document.getElementById('chat-content');

        // Gửi request tới controller
        const response = await fetch('search-flights', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ query: query })
        });

        const result = await response.json();

        // Hiển thị câu trả lời
        chatContent.innerHTML += `<p class="mt-2"><b>Bạn:</b> ${query}</p>`;
        chatContent.innerHTML += `<p class="mt-2 text-blue-600"><b>AI:</b> ${result.message}</p>`;

        // Render kết quả chuyến bay (nếu có)
        if(result.data && result.data.length > 0) {
            result.data.forEach(flight => {
                chatContent.innerHTML += `<div class="ml-4 text-xs border-l-2 pl-2">✈️ ${flight.flight_number} - ${flight.price} VND</div>`;
            });
        }
    }
</script>