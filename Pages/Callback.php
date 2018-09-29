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
     * Default class to serve the Flickr callback
     *
     * @category Class
     * @package  Flickr
     * @author   @cdn <cn@domain.tld>
     * @license  https://github.com/idno/Flickr/blob/master/LICENCE Above
     * @link     https://github.com/cdn
     */
    class Callback extends \Idno\Common\Page
    {

        /**
         * HTTP GET Action
         *
         * @return of The Jedi
         */
        function getContent()
        {
            $this->gatekeeper(); // Logged-in users only
            if ($flickr = \Idno\Core\Idno::site()->plugins()->get('Flickr')) {

                include_once dirname(__FILE__) . '/../external/DPZ/Flickr.php';
                $flickr = new \DPZ\Flickr(
                    \Idno\Core\Idno::site()->config()->flickr['apiKey'],
                    \Idno\Core\Idno::site()->config()->flickr['secret'],
                    \Idno\Core\Idno::site()->config()->getURL() . 'flickr/callback'
                );

                if (!$flickr->authenticate('write')) {
                    \Idno\Core\Idno::site()->logging()->error('Failed to authenticate with Flickr API');
                    $this->forward(\Idno\Core\Idno::site()->config()->getDisplayURL() . 'account/flickr/');
                }

                // $userNsid = $flickr->getOauthData(\DPZ\Flickr::USER_NSID);
                // $userName = $flickr->getOauthData(\DPZ\Flickr::USER_NAME);
                $result['fullname'] = $flickr->getOauthData('user_full_name');
                $result['username'] = $flickr->getOauthData('user_name');

                 $result['token'] = $flickr->getOauthData('oauth_access_token');
                $result['secret'] = $flickr->getOauthData('oauth_access_token_secret');

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
