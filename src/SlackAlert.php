<?php

namespace Spatie\SlackAlerts;

class SlackAlert
{
    protected string $webhookUrlName = 'default';
    protected ?string $channel = null;

    protected ?string $queue = null;

    public function to(string $webhookUrlName): self
    {
        $this->webhookUrlName = $webhookUrlName;

        return $this;
    }

    public function toChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function onQueue(string $queue): self
    {
        $this->queue = $queue;

        return $this;
    }

    public function message(string $text): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        if (! $webhookUrl) {
            return;
        }

        $job = Config::getJob([
            'text' => $text,
            'webhookUrl' => $webhookUrl,
            'channel' => $this->channel,
        ]);

        dispatch($job)
            ->onQueue( $this->queue ?? Config::getQueue() );
    }

    public function blocks(array $blocks): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        if (! $webhookUrl) {
            return;
        }

        $job = Config::getJob([
            'blocks' => $blocks,
            'webhookUrl' => $webhookUrl,
            'channel' => $this->channel,
        ]);

        dispatch($job)
            ->onQueue( $this->queue ?? Config::getQueue() );
    }
}
