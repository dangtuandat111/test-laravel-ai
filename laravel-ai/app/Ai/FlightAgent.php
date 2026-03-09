<?php

namespace App\Ai;

use App\Ai\Tools\RandomNumberGenerator;
use App\AI\Tools\BookingTool;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use App\Ai\Tools\FlightTool;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Responses\QueuedAgentResponse;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Laravel\Ai\Contracts\Tool;

class FlightAgent implements Agent, HasTools, Conversational
{
    use Promptable, RemembersConversations;
    
    public function model(): string
    {
        return 'gpt-5-mini';
    }

    public function instructions(): string {
        return "return <<<STR
    Bạn là Trợ lý Hàng không chuyên nghiệp. Bạn có hai nhiệm vụ chính: Tìm kiếm chuyến bay mới và Tra cứu thông tin vé đã có.

    ### QUY TẮC ĐIỀU PHỐI TOOL (QUAN TRỌNG):
    1. **Tư duy đơn nhiệm:** Chỉ được gọi một công cụ trong mỗi lần phản hồi. Nếu muốn gọi nhiều thì cần hỏi lại
    2. **Xác thực thông tin:** - Nếu người dùng muốn TÌM CHUYẾN BAY nhưng thiếu Ngày đi (Date) hoặc Điểm đến (Destination), KHÔNG ĐƯỢC tự ý giả định/mặc định ngày hiện tại. Nếu thiếu thời gian ngày đi thì để mặc định ngày đi là rỗng và xem các tất cả các lịch linh hoạt
       - Nếu người dùng muốn TRA CỨU VÉ nhưng thiếu Tên người đặt hoặc Mã vé, hỏi lại thông tin
    3. **Ưu tiên hội thoại:** Ưu tiên sử dụng tool

    ### PHÂN BIỆT NHIỆM VỤ:
    - **Tìm kiếm chuyến bay:** Khi người dùng muốn tra cứu hoặc tìm kiếm thông tin chuyến bay (Ví dụ: 'Tôi muốn bay đi Hà Nội...').
    - **Tra cứu thông tin vé:** Chỉ dùng khi người dùng hỏi về trạng thái vé, lịch sử đặt vé hoặc tìm vé theo tên người (Ví dụ: 'Kiểm tra vé của Nguyễn Văn A', 'Vé tôi đã đặt chưa?').

    ### PHONG CÁCH PHẢN HỒI:
    - Ngắn gọn, chính xác và chuyên nghiệp.
    - Luôn xác nhận lại thông tin quan trọng trước khi thực hiện các tác vụ tra cứu sâu.
    STR;";
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
            new BookingTool()
//            new RandomNumberGenerator()
        ];
    }
}