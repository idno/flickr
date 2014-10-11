<?php

    /**
     * Flickr pages
     */

    namespace IdnoPlugins\Flickr\Pages {

        /**
         * Default class to serve Flickr-related account settings
         */
        class Account extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($flickr = \Idno\Core\site()->plugins()->get('Flickr')) {
                    $login_url = $flickr->getAuthURL();
                }
                $t = \Idno\Core\site()->template();
                $body = $t->__(array('login_url' => $login_url))->draw('account/flickr');
                $t->__(array('title' => 'Flickr', 'body' => $body))->drawPage();
            }

            function postContent() {
                $this->gatekeeper(); // Logged-in users only
                if (($this->getInput('remove'))) {
                    $user = \Idno\Core\site()->session()->currentUser();
                    $user->flickr = array();
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your Flickr settings have been removed from your account.');
                }
                $this->forward('/account/flickr/');
            }

        }

    }