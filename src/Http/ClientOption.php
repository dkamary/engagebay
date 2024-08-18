<?php

namespace Aika\Engagebay\Http;

use Aika\Engagebay\Utils\Collection;

class ClientOption
{
    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Set all headers
     *
     * @param Collection<Header>|Header[]|array $headers
     * @return self
     */
    public function setHeaders($headers): self
    {
        $this->options['headers'] = [];

        foreach ($headers as $header) {
            $this->options['headers'][] = ($header instanceof Header)
                ? $header->toArray()
                : $header;
        }

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->options['headers'] ?? [];
    }

    public function addHeader(string $name, mixed $value): self
    {
        if (!isset($this->options['headers'])) $this->options['headers'] = [];
        $this->options['headers'][$name] = $value;

        return $this;
    }

    public function getHeader(string $name): mixed
    {
        return $this->options['headers'][$name] ?? null;
    }

    public function set(string $name, $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function get(string $name): mixed
    {
        return $this->options[$name] ?? null;
    }

    public function verifySSL(bool $verify = false): self
    {
        return $this->set('verify', false);
    }

    public function addFormParams(string $name, $value): self
    {
        if (!isset($this->options['form_params'])) $this->options['form_params'] = [];

        $this->options['form_params'][] = [$name => $value];

        return $this;
    }

    public function setBody(string $content): self
    {
        return $this->set('body', $content);
    }

    public function setJson(array $data): self
    {
        return $this->set('json', $data);
    }

    public function addQuery(string $name, string $value): self
    {
        if (!isset($this->options['query'])) $this->options['query'] = [];

        $this->options['query'][] = [$name => $value];

        return $this;
    }
}
