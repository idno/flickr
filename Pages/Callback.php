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

                    $oauth['flickr']['requesttokenurl'] = "http://www.flickr.com/services/oauth/request_token";
                    $oauth['flickr']['accesstokenurl']  = "https://www.flickr.com/services/oauth/access_token";
                    $oauth['flickr']['authurl']         = "https://www.flickr.com/services/oauth/authorize";

                    $api_key = \Idno\Core\Idno::site()->config()->flickr['apiKey'];
                    $api_secret = \Idno\Core\Idno::site()->config()->flickr['secret'];
                    $callback = \Idno\Core\Idno::site()->config()->getURL() . 'flickr/callback';

                    $oauthc = new \OAuth($api_key, $api_secret,
                                         OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI); //initiate

                    if(empty($_SESSION['frequest_token_secret'])) {
                        //get the request token, and store it
                        $request_token_info = $oauthc->getRequestToken($oauth['flickr']['requesttokenurl']);
                        $_SESSION['frequest_token_secret'] = $request_token_info['oauth_token_secret'];

                        // forward user to authorize url with appropriate permission flag
                        $this->forward("{$oauth['flickr']['authurl']}?oauth_token={$request_token_info['oauth_token']}&perms=write");
                    }
                    // callback
                    else if(empty($_SESSION['faccess_oauth_token'])) {
                        //get the access token - do not forget to save it!
                        $request_token_secret = $_SESSION['frequest_token_secret'];

                        $oauthc->setToken($this->getInput('oauth_token'),$request_token_secret);//user allowed the app, so u…

                        // handshake
                        try {
                            $access_token_info = $oauthc->getAccessToken($oauth['flickr']['accesstokenurl']);
                        } catch(OAuthException $E) {
                           // echo "Exception caught!\n";
                           // echo "Response: ". $E->lastResponse . "\n";
                            error_log('Exception. Response: ' . $E->lastResponse);
                        }

                        $_SESSION['faccess_oauth_token']= $access_token_info['oauth_token'];
                        $_SESSION['faccess_oauth_token_secret']= $access_token_info['oauth_token_secret'];
                    }

                    // authenticated
                    if(isset($_SESSION['faccess_oauth_token'])) {
                        //now fetch current user's profile
                        $access_token = $_SESSION['faccess_oauth_token'];
                        $access_token_secret = $_SESSION['faccess_oauth_token_secret'];
                        $oauthc->setToken($access_token,$access_token_secret);

//fwrite(fopen(dirname(__FILE__) . '/oath.log', 'a'), print_r($_SESSION, true));

                        $result = array();

                        try {

                            $data = $oauthc->fetch(
                                    'https://api.flickr.com/services/rest/?method=flickr.auth.oauth.checkToken&api_key='.
                                    $api_key.'&format=json&nojsoncallback=1'
                                  );

                        } catch(OAuthException $E) {
                            error_log("Exception. Response: ". $E->lastResponse);
                        }

                        $response_info = $oauthc->getLastResponse();
                        $profile_json = json_decode($response_info);

                            echo "<pre>";
                            print_r($profile_json);
                            echo "</pre>";

                        $result['fullname'] = $profile_json->oauth->user->fullname;
                        $result['username'] = $profile_json->oauth->user->username;

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
