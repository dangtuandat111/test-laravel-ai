<?php

namespace App\AI\Tools;

use App\Models\Flights;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Ai\Contracts\Tool;

#[Description('Tìm thông tin máy bay trong database dựa trên trích xuất thông tin hành trình.')]
class FlightTool implements Tool
{
    public function description(): string
    {
        return 'Tìm thông tin máy bay trong database dựa trên trích xuất thông tin hành trình.';
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'from' => $schema->string()
                ->description('Thành phố hoặc mã sân bay xuất phát (ví dụ: HAN, SGN, Hà Nội).')
                // Bỏ dòng "mặc định" ở đây để ép AI phải trích xuất dữ liệu
                ->required(),

            'date' => $schema->string()
                ->description('Ngày khởi hành định dạng YYYY-MM-DD.')
                ->required(),
        ];
    }

    /**
     * Handle the tool request.
     * @param Request $request
     * @return string
     */
    public function handle(Request $request): string
    {
        // 1. Log này SẼ hiện trong storage/logs/laravel.log
        \Log::emergency("=== DEMO: AI ĐÃ TRUY CẬP VÀO DATABASE QUA TOOL ===");

        // 2. Lấy dữ liệu (Giả sử bạn cần lấy tất cả để demo nhanh)
        $flights = Flights::all();

        // 3. BẮT BUỘC trả về qua Response::json ho ặc Response::text
        return $flights->toJson();
    }

    /**
     * BẮT BUỘC: AI cần cái này để biết cách truyền data vào handle
     */
//    public function schema(JsonSchema $schema): array
//    {
//        return [
//            'from' => $schema->string()->description('Mã sân bay đi (IATA)')->required(),
//            'to' => $schema->string()->description('Mã sân bay đến (IATA)')->required(),
//            'date' => $schema->string()->description('Ngày bay định dạng YYYY-MM-DD')->required(),
//            'price' => $schema->number()->description('Giá vé máy bay (VND)')->required(),
//            'database' => $schema->number()->description('Có cần tìm thông tin trong DB không ?')->required(),
//        ];
//    }
}
