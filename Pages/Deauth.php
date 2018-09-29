<?php

    /**
     * Plugin administration
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
     * Default class to remove Flickr account credentials
     *
     * @category Class
     * @package  Flickr
     * @author   @cdn <cn@domain.tld>
     * @license  https://github.com/idno/Flickr/blob/master/LICENCE Above
     * @link     https://github.com/cdn
     */
    class Deauth extends \Idno\Common\Page
    {

        /**
         * HTTP GET Action
         *
         * @return of the Living Dead
         */
        function getContent()
        {
            $this->gatekeeper(); // Logged-in users only
            if ($twitter = \Idno\Core\Idno::site()->plugins()->get('Flickr')) {
                if ($user = \Idno\Core\Idno::site()->session()->currentUser()) {
                    if ($account = $this->getInput('remove')) {
                        if (array_key_exists($account, $user->flickr)) {
                            unset($user->flickr[$account]);
                        } else {
                            $user->flickr = false;
                        }
                    } else {
                        $user->flickr = false;
                    }
                    $user->save();
                    \Idno\Core\Idno::site()->session()->refreshSessionUser($user);
                    if (!empty($user->link_callback)) {
                        error_log($user->link_callback);
                        $this->forward($user->link_callback); exit;
                    }
                }
            }
            $this->forward($_SERVER['HTTP_REFERER']);
        }

        /**
         * HTTP GET Action
         *
         * @return this function returns nothing
         */
        function postContent()
        {
            $this->getContent();
        }

    }

}
