<?php

    /**
     * Flickr pages
     *
     * PHP version 5.6
     *
     * @category Plug-in
     * @package  Photo_POSSE
     * @author   Known, Inc <hello@withknown.com>
     * @license  https://github.com/idno/Known/blob/master/LICENSE Apache
     * @link     https://github.com/Idno/Flickr
     */

?><li <?php if ($_SERVER['REQUEST_URI'] == '/admin/flickr/') {
    echo 'class="active"';
} ?>><a href="<?php echo \Idno\Core\Idno::site()->config()->getDisplayURL()?>admin/flickr/">Flickr</a></li>
