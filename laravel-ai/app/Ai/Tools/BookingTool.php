<?php

namespace App\Ai\Tools;

use App\Models\Booking;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Tools\Request;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Ai\Contracts\Tool;

#[Description('Tìm thông tin vé được đặt dựa trên trích xuất thông tin tìm kiếm')]
class BookingTool implements Tool
{
    public function description(): string
    {
        return 'Tìm thông tin vé được đặt dựa trên trích xuất thông tin tìm kiếm.';
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'flight_id' => $schema->string()
                ->description('Mã chuyến bay (ví dụ: 12345).'),

            'customer_name' => $schema->string()
                ->description('Tên khách hàng đã đặt vé (ví dụ: Zoe).'),

            'status' => $schema->string()
                ->description('Trạng thái đặt vé (ví dụ: confirmed, pending, canceled).')
        ];
    }

    /**
     * Handle the tool request.
     * @param Request $request
     * @return string
     */
    public function handle(Request $request): string
    {
        $bookings = Booking::query();

        if ($request->has('flight_id')) {
            $bookings->where('flight_id', '=', $request['flight_id']);
        }

        if ($request->has('customer_name')) {
            $bookings->where('customer_name', '=', $request['customer_name']);
        }

        if ($request->filled('status')) {
            $bookings->whereDate('status', $request['status']);
        }

        logger()->debug($bookings->toRawSql());
        $bookings = $bookings->get();

        return $bookings->toJson();
    }
}
