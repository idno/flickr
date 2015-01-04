<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\Flickr\Pages {

        /**
         * Default class to serve the homepage
         */
        class Deauth extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($twitter = \Idno\Core\site()->plugins()->get('Flickr')) {
                    if ($user = \Idno\Core\site()->session()->currentUser()) {
                        if ($account = $this->getInput('remove')) {
                            if (array_key_exists($account, $user->flickr)) {
                                unset($user->flickr[$account]);
                            } else {
                                $user->flickr = false;
                            }
                        }
                        $user->save();
                        \Idno\Core\site()->session()->refreshSessionUser($user);
                        if (!empty($user->link_callback)) {
                            error_log($user->link_callback);
                            $this->forward($user->link_callback); exit;
                        }
                    }
                }
                $this->forward($_SERVER['HTTP_REFERER']);
            }

            function postContent() {
                $this->getContent();
            }

        }

    }