<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Files\Document;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Tools\Request;
use Stringable;

class FileContentRetriever implements Tool
{
    /**
     * Định nghĩa các tham số mà AI cần cung cấp để chạy tool.
     * @param JsonSchema $schema
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'file_path' => $schema->string()->description('Đường dẫn file trong uploads')
        ];
    }

    /**
     * Logic thực thi của Tool.
     */
    public function handle(Request $request): string
    {
        logger()->debug($request->filled('file_path'));
        if (!Storage::disk('public')->exists($request->filled('file_path'))) {
            return "Lỗi: Không tìm thấy file tại đường dẫn đã cho.";
        }

        // SDK sẽ tự xử lý việc đọc nội dung tùy theo định dạng file
        return Document::fromStorage($request->filled('file_path'), disk: 'public')->content();
    }

    public function description(): string
    {
        return "Lấy nội dung văn bản từ một file cụ thể khi người dùng hỏi về nó.";
    }
}