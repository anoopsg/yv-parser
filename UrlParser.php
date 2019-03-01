<?php

class UrlParser
{
    /**
     * Video type vimeo.
     * @const
     */
    const TYPE_VIMEO = 'viemo';

    /**
     *Video type youtube.
     * @const
     */
    const TYPE_YOUTUBE = 'youtube';

    /**
     * Input url
     * @var null|string
     */
    protected $url = null;
    /**
     * Processed video type.
     * @var null
     */
    protected $type = null;

    /**
     * Parser constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->identifyService();
    }

    /**
     *Check whether the video type is youtube.
     * @return bool
     */
    public function isYoutube()
    {
        return $this->type === self::TYPE_YOUTUBE;
    }

    /**
     * Check whether the video type is vimeo.
     * @return bool
     */
    public function isVimeo()
    {
        return $this->type === self::TYPE_VIMEO;
    }

    /**
     * Get the Id of vimeo url.
     * @return int
     */
    public function getVimeoId()
    {
        return (int)substr(parse_url($this->url, PHP_URL_PATH), 1);
    }

    /**
     * Get the Id of youtube url.
     * @return array
     */
    public function getYoutubeId()
    {
        $link = $this->url;

        $video_id = explode("?v=", $link); // For videos like http://www.youtube.com/watch?v=...
        if (empty($video_id[1])) {
            $video_id = explode("/v/", $link);
        } // For videos like http://www.youtube.com/watch/v/..

        $video_id = explode("&", $video_id[1]); // Deleting any other params
        $video_id = $video_id[0];

        return $video_id;
    }


    /**
     *Identify the service is Vimeo or youtube.
     */
    public function identifyService()
    {
        $url = $this->url;
        if (preg_match('%(?:https?:)?//(?:(?:www|m)\.)?(youtube(?:-nocookie)?\.com|youtu\.be)\/%i', $url)) {
            $this->type = self::TYPE_YOUTUBE;
        } elseif (preg_match('%(?:https?:)?//(?:[a-z]+\.)*vimeo\.com\/%i', $url)) {
            $this->type = self::TYPE_VIMEO;
        }
    }

    /**
     * Get the Youtube embedded url.
     * @return string
     */
    public function getYoutubeEmbedUrl()
    {
        return "https://www.youtube.com/embed/".
            $this->getYoutubeId()
            ."?enablejsapi=1";
    }

    /**
     *Get the Vimeo embedded url.
     * @return string
     */
    public function getVimeoEmbedUrl()
    {
        return "http://player.vimeo.com/video/".$this->getVimeoId();
    }

}