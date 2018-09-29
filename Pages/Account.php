<?php

    /**
     * Flickr pages
     *
     * @category Plug-in
     * @package  Photo_POSSE
     * @author   Known, Inc <hello@withknown.com>
     * @license  https://github.com/idno/Known/blob/master/LICENSE Apache
     * @link     https://github.com/Idno/Flickr
     */

namespace IdnoPlugins\Flickr\Pages {

    /**
     * Default class to serve Flickr-related account settings
     *
     * @category Class
     * @package  Flickr
     * @author   @cdn <cn@domain.tld>
     * @license  https://github.com/idno/Flickr/blob/master/LICENCE Above
     * @link     https://github.com/cdn
     */
    class Account extends \Idno\Common\Page
    {

        /**
         * HTTP GET Action
         *
         * @return James Bond will
         */
        function getContent()
        {
            $this->gatekeeper(); // Logged-in users only
            if ($flickr = \Idno\Core\Idno::site()->plugins()->get('Flickr')) {
                $login_url = $flickr->getAuthURL();
            }
            $t = \Idno\Core\Idno::site()->template();
            $body = $t->__(array('login_url' => $login_url))->draw('account/flickr');
            $t->__(array('title' => 'Flickr', 'body' => $body))->drawPage();
        }

        /**
         * HTTP POST Action
         *
         * @return Of The Jedi
         */
        function postContent()
        {
            $this->gatekeeper(); // Logged-in users only
            if (($this->getInput('remove'))) {
                $user = \Idno\Core\Idno::site()->session()->currentUser();
                $user->flickr = array();
                $user->save();
                \Idno\Core\Idno::site()->session()->addMessage(\Idno\Core\Idno::site()->language()->_('Your Flickr settings have been removed from your account.'));
            }
            $this->forward(\Idno\Core\Idno::site()->config()->getDisplayURL() . 'account/flickr/');
        }

    }

}
