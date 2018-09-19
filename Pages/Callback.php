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
                if ($flickr = \Idno\Core\Idno::site()->plugins()->get('Flickr')) {

                    $api_key = \Idno\Core\Idno::site()->config()->flickr['apiKey'];
                    $api_secret = \Idno\Core\Idno::site()->config()->flickr['secret'];

                    $callback = \Idno\Core\Idno::site()->config()->getURL() . 'flickr/callback';

                    require_once(dirname(__FILE__) . '/../external/DPZ/Flickr.php');
                    $flickr = new \DPZ\Flickr(\Idno\Core\Idno::site()->config()->flickr['apiKey'],
                                              \Idno\Core\Idno::site()->config()->flickr['secret'],
                                              $callback);

                    if(empty($_SESSION['faccess_oauth_token'])) {

                        if(!$flickr->authenticate('write')) die('Laughing');

                            // $userNsid = $flickr->getOauthData(\DPZ\Flickr::USER_NSID);
                            $userName = $flickr->getOauthData(\DPZ\Flickr::USER_NAME);
                            $userFullName = $flickr->getOauthData(\DPZ\Flickr::USER_FULL_NAME);

                          $result['fullname'] = $userFullName;
                          $result['username'] = $userName;

                         $result['token'] = $flickr->getOauthData('oauth_access_token');
                        $result['secret'] = $flickr->getOauthData('oauth_access_token_secret');
                    }

                    if (!empty($result['token'])) {
                         $user = \Idno\Core\Idno::site()->session()->currentUser();
                         $user->flickr[$result['username']] = array('access_token' => $result['token'], 'secret' => $result['secret'], 'username' => $result['fullname']);
                         $user->save();
                         \Idno\Core\Idno::site()->logging()->log('Flickr user '.$result['username'].' authenticated.');
                    }

                }
                if (!empty($_SESSION['onboarding_passthrough'])) {
                    unset($_SESSION['onboarding_passthrough']);
                    $this->forward(\Idno\Core\Idno::site()->config()->getURL() . 'begin/connect-forwarder');
                }
                $this->forward(\Idno\Core\Idno::site()->config()->getDisplayURL() . 'account/flickr/');
            }

        }

    }
