<?php

namespace App\AI;

use Illuminate\Broadcasting\Channel;
use Laravel\Ai\Contracts\Agent;
use App\Ai\Tools\FlightTool;
use Laravel\Ai\Promptable;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Responses\QueuedAgentResponse;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Laravel\Ai\Contracts\Tool;

class FlightAgent implements Agent
{
    use Promptable;
    
    public function model(): string
    {
        return 'gpt-4o';
    }

    public function instructions(): string {
        return "Bạn là trợ lý đặt vé máy bay. 
            1. Nếu khách muốn tìm thông tin chuyến bay thì:
                Nhiệm vụ: Tìm thông tin máy bay trong database dựa trên trích xuất thông tin hành trình.
                Ngày hiện tại là " . now()->toDateString() . ".
                Quy đổi thành phố sang mã IATA (SGN, HAN, DLI, DAD, ...).
                Nếu không có thông tin cần thiết, hãy trả về null cho trường đó.
                Đối với ngày đi, nếu không có hãy lấy ngày hiện tại. Hãy sử dụng FlightTool tool để truy cập vào database.
                1. Tuyệt đối KHÔNG ĐƯỢC tự bịa ra thông tin chuyến bay.
                2. Bạn BẮT BUỘC phải sử dụng 'FlightTool' để lấy dữ liệu từ Database.
                3. Nếu 'toolCalls' rỗng, câu trả lời của bạn sẽ bị coi là sai.
            2. Nếu muốn tìm thông tin vé thì dùng BookingTool để tra cứu thông tin đặt vé máy bay đã có trong hệ thống.
                Nếu thiếu thông tin (ví dụ: không có tên khách) thì vẫn chạy tools để lấy toàn bộ thông tin";
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new \App\AI\Tools\FlightTool(),
        ];
    }
}