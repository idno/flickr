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
                                } else {
                                    $name = 'Flickr';
                                }

                                if (!$flickrAPI) {
                                    error_log('Failed to connect to Flickr API');
                                }
                                else if (empty($user_details)) {
                                    error_log('Failed to get user_details');
                                }
                                else {
                                    $tags = str_replace('#','',implode(' ', $object->getTags())); // Get string of non-hashtagged tags
                                    try {
                                        $photo_id = $flickrAPI->upload($attachment['url'], $object->getTitle(), $object->getDescription() . "\n\nOriginal: " . $object->getURL(), $tags, array("is_public"=>1), 0);
                                        if (!empty($photo_id)) {
                                            $photo = $flickrAPI->photosGetInfo($photo_id);
                                        	if (!empty($photo['urls']['photopage'])) {
                                        		$object->setPosseLink('flickr',$photo['urls']['photopage'], $name);
                                        		$object->save();
                                        	}
                                        }
                                        else {
                                            error_log("Failed to upload image to Flickr. code={$flickrAPI->getErrorCode()}, error={$flickrAPI->getErrorMessage()}");
                                        }
                                    }
                                    catch (\FlickrApiException $e) {
                                        error_log('Could not post image to Flickr: ' . $e->getMessage());
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

                $flickr = $this;
                $login_url = '';
                //if (!$flickr->hasFlickr()) {
                    if ($flickrAPI = $flickr->connect()) {
                        /* @var \Flickr $flickrAPI */
                        $login_url = $flickrAPI->getAuthUrl('write');
                    }
                //}

                return $login_url;

            }

            /**
             * Connect to Flickr
             * @return bool|\Flickr
             */
            function connect($username = false) {
                if (!empty(\Idno\Core\Idno::site()->config()->flickr)) {
                    require_once(dirname(__FILE__) . '/external/flickr_api.php');
                    $flickr = new \Flickr(array(
                        'api_key' => \Idno\Core\Idno::site()->config()->flickr['apiKey'],
                        'api_secret' => \Idno\Core\Idno::site()->config()->flickr['secret']
                    ));
                    if ($this->hasFlickr()) {
                        if (empty($username)) {
                            if (!empty(\Idno\Core\Idno::site()->session()->currentUser()->flickr['access_token'])) {
                                $flickr->token = \Idno\Core\Idno::site()->session()->currentUser()->flickr['access_token'];
                            }
                        } else {
                            if (!empty(
                                \Idno\Core\Idno::site()->session()->currentUser()->flickr[$username]
                            )) {
                                $flickr->token = \Idno\Core\Idno::site()->session()->currentUser()->flickr[$username]['access_token'];
                            } else if (!empty(\Idno\Core\Idno::site()->session()->currentUser()->flickr['username']) && $username == \Idno\Core\Idno::site()->session()->currentUser()->flickr['username']) {
                                $flickr->token = \Idno\Core\Idno::site()->session()->currentUser()->flickr['access_token'];
                            }
                        }
                    }
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
                    return false;
                }
                if (\Idno\Core\Idno::site()->session()->currentUser()->flickr) {
                    return true;
                }
                return false;
            }

        }

    }
