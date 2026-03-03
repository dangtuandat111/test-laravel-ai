<?php

namespace App\AI;

use App\Mcp\Tools\FlightTool;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Responses\QueuedAgentResponse;
use Laravel\Ai\Responses\StreamableAgentResponse;

class FlightAgent implements Agent
{
    use Promptable;

    public function model(): string
    {
        return 'gpt-5-mini';
    }
    
    public function instructions(): string {
        return "Bạn là trợ lý đặt vé máy bay. Nhiệm vụ: Trích xuất thông tin hành trình.
                Ngày hiện tại là " . now()->toDateString() . ".
                Quy đổi thành phố sang mã IATA (SGN, HAN, DLI, DAD, ...).";
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new FlightTool(),
        ];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'from' => $schema->string()->description('Mã sân bay đi (IATA)'),
            'to' => $schema->string()->description('Mã sân bay đến (IATA)'),
            'date' => $schema->string()->description('Ngày bay định dạng YYYY-MM-DD'),
            'price' => $schema->number()->description('Giá vé máy bay (VND)'),
            'airline' => $schema->string()->description('Tên hãng hàng không'),
        ];
    }

//    public function prompt(string $prompt, array $attachments = [], ?string $provider = null, ?string $model = null): AgentResponse
//    {
//        // TODO: Implement prompt() method.
//        return $this
//            ->prompt($prompt);
//    }
//
//    public function stream(string $prompt, array $attachments = [], array|string|null $provider = null, ?string $model = null): StreamableAgentResponse
//    {
//        // TODO: Implement stream() method.
//        return new StreamableAgentResponse();
//    }
//
//    public function queue(string $prompt, array $attachments = [], array|string|null $provider = null, ?string $model = null): QueuedAgentResponse
//    {
//        // TODO: Implement queue() method.
//        return new QueuedAgentResponse();
//    }
//
//    public function broadcast(string $prompt, Channel|array $channels, array $attachments = [], bool $now = false, ?string $provider = null, ?string $model = null): StreamableAgentResponse
//    {
//        // TODO: Implement broadcast() method.
//        return new StreamableAgentResponse();
//    }
//
//    public function broadcastNow(string $prompt, Channel|array $channels, array $attachments = [], ?string $provider = null, ?string $model = null): StreamableAgentResponse
//    {
//        // TODO: Implement broadcastNow() method.
//        return new StreamableAgentResponse();
//    }
//
//    public function broadcastOnQueue(string $prompt, Channel|array $channels, array $attachments = [], ?string $provider = null, ?string $model = null): QueuedAgentResponse
//    {
//        // TODO: Implement broadcastOnQueue() method.
//        return new QueuedAgentResponse();
//    }
}