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
                if ($flickr = \Idno\Core\Idno::site()->plugins()->get('Flickr')) {
                    $login_url = $flickr->getAuthURL();
                }
                $t = \Idno\Core\Idno::site()->template();
                $body = $t->__(array('login_url' => $login_url))->draw('account/flickr');
                $t->__(array('title' => 'Flickr', 'body' => $body))->drawPage();
            }

            function postContent() {
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