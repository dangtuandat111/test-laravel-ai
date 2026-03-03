<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Finding flight information based on destination, departure date, and budget.')]
class FlightTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        //

        return Response::text('Thông tin vé máy bay: Đi từ HAN đến SGN vào ngày 2024-12-01, giá 1,500,000 VND.');
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'destination' => $schema->string()
                ->description('Mã sân bay đến (ví dụ: HAN, SGN, DAD).'),

            'departure_date' => $schema->string()
                ->description('Ngày khởi hành định dạng YYYY-MM-DD.'),

            'max_price' => $schema->integer()
                ->description('Ngân sách tối đa của khách hàng.')
        ];
    }
}
