<?php

namespace App\AI;

use Illuminate\Broadcasting\Channel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Responses\QueuedAgentResponse;
use Laravel\Ai\Responses\StreamableAgentResponse;

class FlightAgent implements Agent, Conversational, HasTools, HasStructuredOutput
{
    use Promptable;
    
    public function instructions(): string {
        return "Bạn là trợ lý đặt vé máy bay. Nhiệm vụ: Trích xuất thông tin hành trình. 
                Ngày hiện tại là " . now()->toDateString() . ". 
                Quy đổi thành phố sang mã IATA (SGN, HAN, DLI, DAD, ...).";
    }

    public function outputSchema(): array {
        return [
            'to' => 'string|min:3|max:3',
            'date' => 'date_format:Y-m-d',
            'budget' => 'integer|nullable',
            'is_business' => 'boolean'
        ];
    }

    public function prompt(string $prompt, array $attachments = [], ?string $provider = null, ?string $model = null): AgentResponse
    {
        // TODO: Implement prompt() method.
        return (new FlightAgent)
            ->prompt($this->instructions());
    }
}