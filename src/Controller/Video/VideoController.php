<?php


namespace App\Controller\Video;


class VideoController
{
/* code JS
// ---------------------
// VIDEO YT : clean URL
    function video_cleanURL_YT($video_url)
    {
        if (!empty($video_url)) {
            $video_url = str_replace('youtu.be/', 'www.youtube.com/embed/', $video_url);
            $video_url = str_replace('www.youtube.com/watch?v=', 'www.youtube.com/embed/', $video_url);
        }
        // -----------------
        return $video_url;
    };
// ---------------------
// VIDEO YT : iframe
    function video_iframe_YT($video_url)
    {
        $video_iframe = '';
        // -----------------
        if (!empty($video_url)) {
            $video_url = video_cleanURL_YT($video_url);
            $video_iframe = '<iframe width="560" height="315" src="'.$video_url.'"  frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        }
        // -----------------
        return $video_iframe;
    };

}

// VIDEO YT : image de la vidéo
function video_img_YT($video_url)
{
    $video_embed_Img = '';
    if (!empty($video_url)) {
        $video_url = video_cleanURL_YT($video_url);
        $video_ID_array = explode('/', $video_url);
        $video_ID = $video_ID_array[count($video_ID_array) - 1]; // élément de l'URL après le dernier /
        $video_embed_Img = 'https://i3.ytimg.com/vi/'.$video_ID.'/hqdefault.jpg'; //pass 0,1,2,3 for different sizes like 0.jpg, 1.jpg
    }
    // -----------------
    return '<img src="'.$video_embed_Img.'" />';
*/

}