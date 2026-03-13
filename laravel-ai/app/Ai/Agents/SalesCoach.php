<?php

namespace App\Ai\Agents;

use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Stringable;

class SalesCoach implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function model(): string
    {
        return 'gpt-5-mini';
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Bạn là một trợ lý đa năng. 
        Nếu người dùng hỏi về nội dung của một tài liệu, hãy sử dụng tool FileContentRetriever để đọc nó.';    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new \App\Ai\Tools\FileContentRetriever(),
        ];
    }
}
