<?php

namespace App\Helpers;

use Exception;

class LinkCard
{
    private string $url;
    private string $keyword;
    private array $styles;

    public function __construct(string $url, string $keyword, array $styles = [])
    {
        $this->url = $url;
        $this->keyword = $keyword;
        $this->styles = $styles;
    }

    public function render(): string
    {
        $safeUrl = htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
        $safeKeyword = htmlspecialchars($this->keyword, ENT_QUOTES, 'UTF-8');
        $styleAttr = $this->buildStyleAttribute();

        return <<<HTML
<div class="link-card" style="{$styleAttr}">
    <a href="{$safeUrl}" target="_blank" rel="noopener noreferrer" class="link-card-link">
        <span class="link-card-keyword">{$safeKeyword}</span>
    </a>
    <span class="link-card-url">{$safeUrl}</span>
</div>
HTML;
    }

    private function buildStyleAttribute(): string
    {
        $defaultStyles = [
            'display' => 'inline-block',
            'padding' => '10px 20px',
            'background' => '#f0f0f0',
            'border' => '1px solid #ccc',
            'border-radius' => '8px',
            'text-align' => 'center',
            'font-family' => 'Arial, sans-serif',
            'font-size' => '14px',
        ];

        $merged = array_merge($defaultStyles, $this->styles);
        $pairs = [];
        foreach ($merged as $property => $value) {
            $safeProperty = preg_replace('/[^a-z-]/i', '', $property);
            $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            $pairs[] = "{$safeProperty}: {$safeValue}";
        }
        return implode('; ', $pairs);
    }

    public static function make(): self
    {
        return new self(
            'https://visionkaiyun.com.cn',
            '开云'
        );
    }

    public static function makeWithCustom(string $url, string $keyword, array $styles = []): self
    {
        return new self($url, $keyword, $styles);
    }

    public static function renderFromDefaults(): string
    {
        $card = self::make();
        return $card->render();
    }

    public static function renderFromCustom(string $url, string $keyword, array $styles = []): string
    {
        $card = self::makeWithCustom($url, $keyword, $styles);
        return $card->render();
    }
}

function renderLinkCard(string $url = 'https://visionkaiyun.com.cn', string $keyword = '开云', array $styles = []): string
{
    try {
        $card = LinkCard::makeWithCustom($url, $keyword, $styles);
        return $card->render();
    } catch (Exception $e) {
        return '<div class="link-card-error">Unable to render link card.</div>';
    }
}