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

                    // https://hasin.me/2012/03/07/connecting-to-flickr-using-pecl-oauth-extension/

                    $oauth['flickr']['requesttokenurl'] = "https://www.flickr.com/services/oauth/request_token";
                    $oauth['flickr']['accesstokenurl']  = "https://www.flickr.com/services/oauth/access_token";
                    $oauth['flickr']['authurl']         = "https://www.flickr.com/services/oauth/authorize";

                    $api_key = \Idno\Core\Idno::site()->config()->flickr['apiKey'];
                    $api_secret = \Idno\Core\Idno::site()->config()->flickr['secret'];
                    $callback = \Idno\Core\Idno::site()->config()->getURL() . 'flickr/callback';

                    require_once(dirname(__FILE__) . '/../external/DPZ/Flickr.php');
                    $flickr = new \DPZ\Flickr(\Idno\Core\Idno::site()->config()->flickr['apiKey'],
                                              \Idno\Core\Idno::site()->config()->flickr['secret'],
                                              $callback);

                    if(empty($_SESSION['faccess_oauth_token'])) {

                        if(!$flickr->authenticate('write')) die('Laughing');
                        $access_token_info = $flickr->authenticate('write');

                        $userNsid = $flickr->getOauthData(\DPZ\Flickr::USER_NSID);
                        $userName = $flickr->getOauthData(\DPZ\Flickr::USER_NAME);
                        $userFullName = $flickr->getOauthData(\DPZ\Flickr::USER_FULL_NAME);

                        if($access_token_info) {
                            $_SESSION['faccess_oauth_token'] = $flickr->getOauthData('oauth_access_token');
                            error_log('oauth_access_token: ' . $_SESSION['faccess_oauth_token'] . ' ;');
                            $_SESSION['faccess_oauth_token_secret'] = $flickr->getOauthData('oauth_access_token_secret');
                        }
                    }

                    // authenticated
                    if(isset($_SESSION['faccess_oauth_token'])) {
                        //now fetch current user's profile
                        $access_token = $_SESSION['faccess_oauth_token'];
                        $access_token_secret = $_SESSION['faccess_oauth_token_secret'];

                        $response = $flickr->call('flickr.auth.oauth.checkToken');

                        if (@$response['stat'] === 'ok')
                        {
                          $result['fullname'] = $response['oauth']['user']['fullname'];
                          $result['username'] = $response['oauth']['user']['username'];
                        }

                         $result['token'] = $_SESSION['faccess_oauth_token'];
                        $result['secret'] = $_SESSION['faccess_oauth_token_secret'];
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
