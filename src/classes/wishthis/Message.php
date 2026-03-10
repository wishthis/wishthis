<?php

namespace wishthis;

class Message
{
    public function __construct(
        private string $content = '',
        private string $header = '',
        private MessageType $type = MessageType::INFO
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getType(): MessageType
    {
        return $this->type;
    }
}
