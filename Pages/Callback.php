<?php

    /**
     * Flickr pages
     */

    namespace IdnoPlugins\Flickr\Pages {

        /**
         * Default class to serve the Flickr callback
         */
        class Callback extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($flickr = \Idno\Core\site()->plugins()->get('Flickr')) {
                    /* @var \phpFlickr $flickrAPI */
                    if ($flickrAPI = $flickr->connect()) {
                        if ($frob = $this->getInput('frob')) {
                            $result = $flickrAPI->getFrobToken($frob);
                            if (!empty($result['token'])) {
                                $flickr = ['access_token' => $result['token']];
                                \Idno\Core\site()->session()->currentUser()->flickr = $flickr;
                                \Idno\Core\site()->session()->currentUser()->save();
                            }
                        }
                    }
                }
                if (!empty($_SESSION['onboarding_passthrough'])) {
                    unset($_SESSION['onboarding_passthrough']);
                    $this->forward(\Idno\Core\site()->config()->getURL() . 'begin/connect');
                }
                $this->forward('/account/flickr/');
            }

        }

    }