<?php

namespace App\Mcp\Tools;

use App\Models\Booking;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Illuminate\Support\Facades\Log;

#[Description('Tra cứu thông tin đặt vé máy bay đã có trong hệ thống.')]
class BookingTool extends Tool
{
    /**
     * Định nghĩa Schema để AI biết cách tìm kiếm.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'booking_id' => $schema->integer()
                ->description('ID của mã đặt vé (nếu khách cung cấp số)')
                ->optional(),

            'customer_name' => $schema->string()
                ->description('Họ tên khách hàng để tìm kiếm')
                ->optional(),
        ];
    }

    /**
     * Thực thi truy vấn Database.
     */
    public function handle(Request $request): Response
    {
        Log::emergency("=== JARVIS ĐANG TRA CỨU DATABASE BOOKINGS ===");

        $bookingId = $request->argument('booking_id');
        $customerName = $request->argument('customer_name');

        // Logic tìm kiếm linh hoạt
        $query = Booking::query();

        if ($bookingId) {
            $query->where('id', $bookingId);
        } elseif ($customerName) {
            $query->where('customer_name', 'like', "%{$customerName}%");
        }

        $results = $query->get();

        if ($results->isEmpty()) {
            return Response::json(['status' => 'not_found', 'message' => 'Không tìm thấy thông tin đặt vé nào.']);
        }

        return Response::json([
            'status' => 'success',
            'data' => $results->toArray()
        ]);
    }
}