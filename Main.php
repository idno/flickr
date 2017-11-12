<?php

    namespace IdnoPlugins\Flickr {

        class Main extends \Idno\Common\Plugin {

            function registerPages() {
                // Deauth URL
                    \Idno\Core\Idno::site()->addPageHandler('flickr/deauth','\IdnoPlugins\Flickr\Pages\Deauth',true);
                // Register the callback URL
                    \Idno\Core\Idno::site()->addPageHandler('flickr/callback','\IdnoPlugins\Flickr\Pages\Callback',true);
                // Register admin settings
                    \Idno\Core\Idno::site()->addPageHandler('admin/flickr','\IdnoPlugins\Flickr\Pages\Admin');
                // Register settings page
                    \Idno\Core\Idno::site()->addPageHandler('account/flickr','\IdnoPlugins\Flickr\Pages\Account');

                /** Template extensions */
                // Add menu items to account & administration screens
                    \Idno\Core\Idno::site()->template()->extendTemplate('admin/menu/items','admin/flickr/menu');
                    \Idno\Core\Idno::site()->template()->extendTemplate('account/menu/items','account/flickr/menu');
                    \Idno\Core\Idno::site()->template()->extendTemplate('onboarding/connect/networks','onboarding/connect/flickr');
            }

            function registerEventHooks() {

                \Idno\Core\Idno::site()->syndication()->registerService('flickr', function() {
                    return $this->hasFlickr();
                }, array('image'));

                \Idno\Core\Idno::site()->addEventHook('user/auth/success', function(\Idno\Core\Event $event) {
                    if ($this->hasFlickr()) {
                        if (is_array(\Idno\Core\Idno::site()->session()->currentUser()->flickr)) {
                            foreach(\Idno\Core\Idno::site()->session()->currentUser()->flickr as $username => $details) {
                                if (!in_array($username, ['access_token','username'])) {
                                    \Idno\Core\Idno::site()->syndication()->registerServiceAccount('flickr', $username, $details['username']);
                                }
                            }
                            if (!empty(\Idno\Core\Idno::site()->session()->currentUser()->flickr['username'])) {
                                \Idno\Core\Idno::site()->syndication()->registerServiceAccount('flickr', \Idno\Core\Idno::site()->session()->currentUser()->flickr['username'], \Idno\Core\Idno::site()->session()->currentUser()->flickr['username']);
                            }
                        }
                    }
                });

                // Push "images" to Flickr
                \Idno\Core\Idno::site()->addEventHook('post/image/flickr',function(\Idno\Core\Event $event) {
                    fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "push to Flickr\n");
                    $eventdata = $event->data();
                    $object = $eventdata['object'];
                    if ($attachments = $object->getAttachments()) {
                        foreach($attachments as $attachment) {
                            if ($this->hasFlickr()) {
                                if (!empty($eventdata['syndication_account'])) {
                                    $flickrAPI  = $this->connect($eventdata['syndication_account']);
                                    $user_details = \Idno\Core\Idno::site()->session()->currentUser()->flickr[$eventdata['syndication_account']];
                                } else {
                                    $flickrAPI  = $this->connect();
                                    $user_details = \Idno\Core\Idno::site()->session()->currentUser()->flickr;
                                }

                                if (!empty($user_details['username'])) {
                                    $name = $user_details['username'];
                                   $token = $user_details['access_token'];
                                } else {
                                    $name = 'Flickr';
                                }

                                fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "$name:$token\n");

                                if (!$flickrAPI) {
                                    error_log('Failed to connect to Flickr API');
                                }
                                else if (empty($user_details)) {
                                    error_log('Failed to get user_details');
                                }
                                else {
                                    $tags = str_replace('#','',implode(' ', $object->getTags())); // Get string of non-hashtagged tags
                                    try {

//         function upload($photo, $title='', $description='', $tags='', $perms='', $async=1, &$info=NULL) {

// $attachment['url'], html_entity_decode($object->getTitle()),
// html_entity_decode($object->getDescription()) . "\n\nOriginal: " . $object->getURL(), $tags, array("is_public"=>1), 0

            $title = html_entity_decode($object->getTitle());
            $photo = $attachment['url'];
            fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "\$photo:$photo\n");
            $perms = array("is_public"=>1);
            $description = html_entity_decode($object->getDescription()) . "\n\nOriginal: " . $object->getURL();

/*
                            if ($bytes = \Idno\Entities\File::getFileDataFromAttachment($attachment)) {
                                $media = array();
                                $filename = tempnam(sys_get_temp_dir(), 'idnoflickr');
                                file_put_contents($filename, $bytes);

                                CURLFile $filename
                                '@' . $filename

                                $params = $media;
*/

            $url = parse_url($photo);
            if(isset($url['scheme'])) {
                fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), 'isset($url[\'scheme\']): ' . $url['scheme']."\n");
                $stream = fopen($photo,'r');
                $tmpf = tempnam('/var/tmp','G2F');
                file_put_contents($tmpf, $stream);
                fclose($stream);
                $params['photo'] = $tmpf;
            } else $params['photo'] = $photo;

// public/Uploads/charlienovemb.re/1/6/9/e/169e16aec2f99583240a8ea2712003b0.file

            $info = filesize($params['photo']);
                                fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "\$info:$info\n");
            if($title)       $params['title']       = $title;
            if($description) $params['description'] = $description;
            if($tags)        $params['tags']        = $tags;  // Space-separated string
            if($perms) {
                if(isset($perms['is_public'])) $params['is_public'] = $perms['is_public'];
                if(isset($perms['is_friend'])) $params['is_friend'] = $perms['is_friend'];
                if(isset($perms['is_family'])) $params['is_family'] = $perms['is_family'];
            }

                $photo = $params['photo'];

                if(version_compare(phpversion(), '5.5', '>=')) {
                    $params['photo'] = new \CURLFile($photo);
                } else {
                    $params['photo'] = '@'.$photo;
                }

            if($async)       $params['async']       = $async;

//$attachment['url'], html_entity_decode($object->getTitle()), html_entity_decode($object->getDescription()) . "\n\nOriginal: " . $object->getURL(), $tags, array("is_public"=>1), 0

//                                            fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), print_r($params, true) . "\n"); // !

//                                        $photo_id = $flickrAPI->upload($attachment['url'], html_entity_decode($object->getTitle()), html_entity_decode($object->getDescription()) . "\n\nOriginal: " . $object->getURL(), $tags, array("is_public"=>1), 0);
                                        $photo_id = $flickrAPI->upload($params);

// Array ( [stat] => fail [err] => Array ( ) ) 
// Array ( [stat] => ok [photoid] => Array ( [_content] => 38283605892 ) )

                                        $ok = @$photo_id['stat'];

                                        if ($ok == 'ok') {
                                            $photo = $flickrAPI->call('flickr.photos.getInfo',
                                                                       array('photo_id' => $photo_id['photoid']['_content']));

                                        	if ($photo['photo']['urls']['url'][0]['type'] == 'photopage') {
                                                    fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), $photo['photo']['urls']['url'][0]['_content']."\n");
                                        		$object->setPosseLink('flickr',$photo['photo']['urls']['url'][0]['_content'], $name);
                                        		$object->save();
                                        	}
                                            \Idno\Core\Idno::site()->logging()->log($photo_id['photoid']['_content'] . ' pushed to Flickr.');
//                                            fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "pushed to Flickr\n");
/*
                            }
*/
                                        }
                                        else {
                                            error_log("Failed to upload image to Flickr. code={$flickrAPI->getErrorCode()}, error={$flickrAPI->getErrorMessage()}");
                                            fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "No push to Flickr\n"); // !
                                        }
                                    }
                                    catch (\FlickrApiException $e) {
                                        error_log('Could not post image to Flickr: ' . $e->getMessage());
                                    //    fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "Not pushed to Flickr\n");
                                    }
                                }
                            }
                        }
                    }
                });
            }

            /**
             * Return the URL required to authenticate with the API
             * @return string
             */
            function getAuthURL() {

                $login_url = \Idno\Core\Idno::site()->config()->getURL() . 'flickr/callback';
                return $login_url;

            }

            /**
             * Connect to Flickr
             * @return bool|\Flickr
             */
            function connect($username = false) {
                if (!empty(\Idno\Core\Idno::site()->config()->flickr)) {
                    require_once(dirname(__FILE__) . '/external/DPZ/Flickr.php');
/*                    $flickr = new \Flickr(array(
                        'api_key' => \Idno\Core\Idno::site()->config()->flickr['apiKey'],
                        'api_secret' => \Idno\Core\Idno::site()->config()->flickr['secret']
                    ));*/
                    $flickr = new \DPZ\Flickr(\Idno\Core\Idno::site()->config()->flickr['apiKey'],
                                              \Idno\Core\Idno::site()->config()->flickr['secret']);
                    if ($this->hasFlickr()) {
                        if (empty($username)) {
                            if (!empty(\Idno\Core\Idno::site()->session()->currentUser()->flickr['access_token'])) {
//                                $flickr->token = \Idno\Core\Idno::site()->session()->currentUser()->flickr['access_token'];
//                                $flickr->secret = \Idno\Core\Idno::site()->session()->currentUser()->flickr['secret'];
//            $this->setOauthData(self::OAUTH_ACCESS_TOKEN, $accessToken);
//            $this->setOauthData(self::OAUTH_ACCESS_TOKEN_SECRET, $accessTokenSecret);
// need public setter

// https://github.com/dopiaza/DPZFlickr/pull/8 https://github.com/lucasgd/DPZFlickr

                                $flickr->isValidOauthToken(\Idno\Core\Idno::site()->session()->currentUser()->flickr['access_token'],
                                                           \Idno\Core\Idno::site()->session()->currentUser()->flickr['secret']);
                            }
                        } else {
                            if (!empty(
                                \Idno\Core\Idno::site()->session()->currentUser()->flickr[$username]
                            )) {
                                $flickr->isValidOauthToken(\Idno\Core\Idno::site()->session()->currentUser()->flickr[$username]['access_token'],
                                                           \Idno\Core\Idno::site()->session()->currentUser()->flickr[$username]['secret']);
                            } else if (!empty(\Idno\Core\Idno::site()->session()->currentUser()->flickr['username']) && $username == \Idno\Core\Idno::site()->session()->currentUser()->flickr['username']) {
                                $flickr->isValidOauthToken(\Idno\Core\Idno::site()->session()->currentUser()->flickr['access_token'],
                                                           \Idno\Core\Idno::site()->session()->currentUser()->flickr['secret']);
                            }
                        }
                    }

//                    fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "Object->token: {$flickr->getOauthData('oauth_access_token')}\n");
//                    fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "Object->secret: {$flickr->getOauthData('oauth_access_token_secret')}\n");

                    return $flickr;
                }
                return false;
            }

            /**
             * Can the current user use Flickr?
             * @return bool
             */
            function hasFlickr() {
                if (!\Idno\Core\Idno::site()->session()->currentUser()) {
                    fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "No Flickr()\n");
                    return false;
                }
                if (\Idno\Core\Idno::site()->session()->currentUser()->flickr) {
                    fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "Has Flickr()\n");
                    return true;
                }
                fwrite(fopen(dirname(__FILE__) . '/Pages/oath.log', 'a'), "Has Failed\n");
                return false;
            }

        }

    }
