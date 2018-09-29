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

namespace IdnoPlugins\Flickr\Pages {

    /**
     * Default class to serve Flickr settings in administration
     *
     * @category Class
     * @package  Flickr
     * @author   @cdn <cn@domain.tld>
     * @license  https://github.com/idno/Flickr/blob/master/LICENCE Above
     * @link     https://github.com/cdn
     */
    class Admin extends \Idno\Common\Page
    {

        /**
         * HTTP GET Action
         *
         * @return of the King
         */
        function getContent()
        {
            $this->adminGatekeeper(); // Admins only
            $t = \Idno\Core\Idno::site()->template();
            $body = $t->draw('admin/flickr');
            $t->__(array('title' => 'Flickr', 'body' => $body))->drawPage();
        }

        /**
         * HTTP POST Action
         *
         * @return to sender
         */
        function postContent()
        {
            $this->adminGatekeeper(); // Admins only
            $apiKey = $this->getInput('apiKey');
            $secret = $this->getInput('secret');
            \Idno\Core\Idno::site()->config->config['flickr'] = array(
                'apiKey' => $apiKey,
                'secret' => $secret
            );
            \Idno\Core\Idno::site()->config()->save();
            \Idno\Core\Idno::site()->session()->addMessage(\Idno\Core\Idno::site()->language()->_('Your Flickr application details were saved.'));
            $this->forward(\Idno\Core\Idno::site()->config()->getDisplayURL() . 'admin/flickr/');
        }

    }

}
