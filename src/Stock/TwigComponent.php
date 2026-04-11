<?php

namespace App\Stock;

use Symfony\AI\Platform\Message\MessageInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('stock')]
final class TwigComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $message = null;

    public function __construct(
        private readonly Chat $chat,
    ) {
    }

    /**
     * @return MessageInterface[]
     */
    public function getMessages(): array
    {
        return $this->chat->loadMessages()->withoutSystemMessage()->getMessages();
    }

    #[LiveAction]
    public function submit(): void
    {
        if (null === $this->message || '' === trim($this->message)) {
            return;
        }

        $this->chat->submitMessage($this->message);

        $this->message = null;
    }

    #[LiveAction]
    public function reset(): void
    {
        $this->chat->reset();
    }
}
