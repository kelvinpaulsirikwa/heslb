<?php

namespace App\Services;

class LinkService
{
    /**
     * Get a link from the configuration
     *
     * @param string $key The dot-notation key for the link
     * @param array $replacements Key-value pairs for placeholder replacements
     * @return string|null
     */
    public static function get(string $key, array $replacements = []): ?string
    {
        $link = config("links.{$key}");
        
        if (!$link) {
            return null;
        }

        // Replace placeholders in the link
        foreach ($replacements as $placeholder => $value) {
            $link = str_replace("{{$placeholder}}", $value, $link);
        }

        return $link;
    }

    /**
     * Get all social media links
     *
     * @return array
     */
    public static function getSocialMedia(): array
    {
        return config('links.social_media', []);
    }

    /**
     * Get all HESLB system links
     *
     * @return array
     */
    public static function getHeslbSystems(): array
    {
        return config('links.heslb_systems', []);
    }

    /**
     * Get all CDN links
     *
     * @return array
     */
    public static function getCdn(): array
    {
        return config('links.cdn', []);
    }

    /**
     * Get YouTube thumbnail URL
     *
     * @param string $videoId
     * @param string $quality 'maxres' or 'hq'
     * @return string
     */
    public static function getYoutubeThumbnail(string $videoId, string $quality = 'maxres'): string
    {
        $key = $quality === 'hq' ? 'youtube.thumbnail_hq' : 'youtube.thumbnail_maxres';
        return self::get($key, ['video_id' => $videoId]);
    }

    /**
     * Get YouTube embed URL
     *
     * @param string $videoId
     * @param array $params Additional parameters for the embed URL
     * @return string
     */
    public static function getYoutubeEmbed(string $videoId, array $params = []): string
    {
        $baseUrl = self::get('youtube.embed_base') . $videoId;
        
        if (!empty($params)) {
            $baseUrl .= '?' . http_build_query($params);
        }

        return $baseUrl;
    }

    /**
     * Get all contact information
     *
     * @return array
     */
    public static function getContact(): array
    {
        return config('links.contact', []);
    }

    /**
     * Get phone number (formatted for display)
     *
     * @return string
     */
    public static function getPhone(): string
    {
        return self::get('contact.phone') ?? '';
    }

    /**
     * Get phone number as tel: link
     *
     * @return string
     */
    public static function getPhoneTel(): string
    {
        return self::get('contact.phone_tel') ?? '';
    }

    /**
     * Get email address
     *
     * @return string
     */
    public static function getEmail(): string
    {
        return self::get('contact.email') ?? '';
    }

    /**
     * Get email as mailto: link
     *
     * @return string
     */
    public static function getEmailMailto(): string
    {
        return self::get('contact.email_mailto') ?? '';
    }

    /**
     * Get office address
     *
     * @return string
     */
    public static function getAddress(): string
    {
        return self::get('contact.address') ?? '';
    }

    /**
     * Check if a link exists in configuration
     *
     * @param string $key
     * @return bool
     */
    public static function exists(string $key): bool
    {
        return config("links.{$key}") !== null;
    }
}
