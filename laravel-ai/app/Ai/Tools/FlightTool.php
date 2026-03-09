<?php

namespace App\Ai\Tools;

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
        return 'Tìm thông tin máy bay trong database dựa trên trích xuất thông tin hành trình. Nếu không có thông tin ngày đi, hãy để là rỗng';
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
                ->description('Thành phố hoặc mã sân bay xuất phát (ví dụ: HAN, SGN, Hà Nội).'),

            'to' => $schema->string()
                ->description('Thành phố hoặc mã sân bay xuất phát (ví dụ: HAN, SGN, Hà Nội).'),

            'date' => $schema->string()
                ->description('Ngày khởi hành định dạng YYYY-MM-DD.')
        ];
    }

    /**
     * Handle the tool request.
     * @param Request $request
     * @return string
     */
    public function handle(Request $request): string
    {
        $flights = Flights::query();
        
        if ($request->filled('from')) {
            $flights->where('from_code', '=', $request['from']);
        }
        
        if ($request->filled('to')) {
            $flights->where('to_code', '=', $request['to']);
        }
        
        if ($request->filled('date')) {
            $flights->whereDate('departure_at', $request['date']);
        }
        
        logger()->debug($flights->toRawSql());
        $flights = $flights->get();

        return $flights->toJson();
    }
}
