<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Chat AI Chuyến Bay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Tùy chỉnh thanh cuộn cho đẹp giống điện thoại */
        #chat-content::-webkit-scrollbar {
            width: 4px;
        }
        #chat-content::-webkit-scrollbar-thumb {
            background-color: #e5e7eb;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gray-200 h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden h-[600px]">

    <div class="bg-indigo-600 p-4 text-white flex items-center shadow-md">
        <div class="relative">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-indigo-600 text-xl shadow-inner">
                ✈️
            </div>
            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-indigo-600 rounded-full"></div>
        </div>
        <div class="ml-3">
            <h2 class="font-bold text-sm">Assistant</h2>
            {{--            <p class="text-[10px] opacity-80">Phản hồi tức thì</p>--}}
        </div>
    </div>

    <div id="chat-content" class="flex-1 overflow-y-auto p-4 space-y-4 bg-[#f0f2f5]">
        <div class="flex justify-start">
            <div class="bg-white text-gray-800 p-3 rounded-2xl rounded-bl-none max-w-[85%] text-sm shadow-sm border border-gray-100">
                T có thể giúp gì cho bạn hôm nay ?
            </div>
        </div>
    </div>

    <div id="typing-loader" class="hidden px-4 py-2">
        <div class="flex items-center gap-1 bg-white w-12 p-2 rounded-full shadow-sm border border-gray-100">
            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></span>
            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce [animation-delay:0.4s]"></span>
        </div>
    </div>

    <div class="p-4 bg-white border-t flex items-center gap-2">
        <input type="text" id="user-query"
               class="flex-1 bg-gray-100 border-none p-3 rounded-full text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
               placeholder="Nhập tin nhắn...">

        <button onclick="askAI()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-full transition-transform active:scale-90 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
            </svg>
        </button>
    </div>
</div>

<script>
    const chatContent = document.getElementById('chat-content');
    const loader = document.getElementById('typing-loader');

    async function askAI() {
        const queryInput = document.getElementById('user-query');
        const message = queryInput.value.trim();

        if (!message) return;

        // 1. Render tin nhắn User
        renderMessage('user', message);
        queryInput.value = '';

        // Hiện hiệu ứng "đang gõ"
        loader.classList.remove('hidden');
        scrollToBottom();

        try {
            // Giả lập gọi API (Trong thực tế bạn thay URL của bạn vào)
            // const response = await fetch('search-flights', { ... });
            const response = await fetch('/chat/stream', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message: message, conversationId: localStorage.getItem('conversationId') || '' })
            });
            
            renderMessage('ai', '');
            const aiMessages = chatContent.querySelectorAll('.flex.justify-start.animate-slideIn');
            const lastAiMessage = aiMessages[aiMessages.length - 1].getElementsByClassName('bg-white')[0];

            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            let fullContent = "";

            while (true) {
                const { value, done } = await reader.read();
                if (done) break;

                const chunk = decoder.decode(value, { stream: true });

                // Laravel AI SDK gửi dữ liệu theo định dạng SSE (data: {...})
                const lines = chunk.split('\n');
                for (const line of lines) {
                    const data = line.replace(/^data: /, '').trim();
                    if (!data || data === '[DONE]') continue;

                    try {
                        const json = JSON.parse(data);

                        // 'text-delta' là sự kiện chuẩn của Laravel AI khi có chữ mới
                        if (json.type === 'text_delta') {
                            fullContent += json.delta;
                            // Cập nhật tin nhắn AI liên tục
                            lastAiMessage.innerText = fullContent;
                        }

                        if (json.type === 'attachment' && json.mime_type.startsWith('image/')) {
                            const imgTag = `
                                <div class="mt-2 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                    <img src="${json.url}" class="max-w-full h-auto object-contain" alt="AI Generated Image" 
                                         onload="scrollToBottom()"> 
                                </div>`;

                            // Chèn ảnh vào cuối vùng chứa tin nhắn
                            lastAiMessage.insertAdjacentHTML('beforeend', imgTag);
                        }
                    } catch (e) {
                        console.error("Lỗi parse JSON dòng:", data);
                    }
                }
            }

            loader.classList.add('hidden');
            // localStorage.setItem('conversationId', result.conversationId);
            //
            // renderMessage('ai', result.message, result.data);
        } catch (error) {
            loader.classList.add('hidden');
            renderMessage('ai', "Rất tiếc, đã có lỗi xảy ra. Vui lòng thử lại sau.");
        }
    }

    function renderMessage(sender, text, data = []) {
        const isUser = sender === 'user';

        let flightHtml = '';
        const messageHtml = `
                <div class="flex ${isUser ? 'justify-end' : 'justify-start'} animate-slideIn">
                    <div class="${isUser ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-100 rounded-bl-none'} 
                                p-3 rounded-2xl max-w-[85%] text-sm shadow-sm">
                        ${text}
                        ${flightHtml}
                    </div>
                </div>`;

        chatContent.insertAdjacentHTML('beforeend', messageHtml);
        scrollToBottom();
    }

    function scrollToBottom() {
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    // Bắt sự kiện Enter
    document.getElementById('user-query').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') askAI();
    });
</script>

<style>
    /* Animation cho mượt mà */
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slideIn { animation: slideIn 0.3s ease-out; }
</style>
</body>
</html>