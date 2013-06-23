<?php

    namespace IdnoPlugins\Flickr {

        class Main extends \Idno\Common\Plugin {

            function registerPages() {
                // Register the callback URL
                    \Idno\Core\site()->addPageHandler('flickr/callback','\IdnoPlugins\Flickr\Pages\Callback');
                // Register admin settings
                    \Idno\Core\site()->addPageHandler('admin/flickr','\IdnoPlugins\Flickr\Pages\Admin');
                // Register settings page
                    \Idno\Core\site()->addPageHandler('account/flickr','\IdnoPlugins\Flickr\Pages\Account');

                /** Template extensions */
                // Add menu items to account & administration screens
                    \Idno\Core\site()->template()->extendTemplate('admin/menu/items','admin/flickr/menu');
                    \Idno\Core\site()->template()->extendTemplate('account/menu/items','account/flickr/menu');
            }

            function registerEventHooks() {
                // Push "images" to Flickr
                \Idno\Core\site()->addEventHook('post/image',function(\Idno\Core\Event $event) {
                    $object = $event->data()['object'];
                    if ($attachments = $object->getAttachments()) {
                        foreach($attachments as $attachment) {
                            if ($this->hasFlickr()) {
                                if ($flickrAPI = $this->connect()) {
                                    $flickrAPI->token = (\Idno\Core\site()->session()->currentUser()->flickr['access_token']);
                                    $tags = str_replace('#','',implode(' ', $object->getTags())); // Get string of non-hashtagged tags
                                    try {
                                        $flickrAPI->upload($attachment['url'], $object->getTitle(), $object->getDescription() . "\n\nOriginal: " . $object->getURL(), $tags, ["is_public"=>1]);
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
             * Connect to Flickr
             * @return bool|\Flickr
             */
            function connect() {
                if (!empty(\Idno\Core\site()->config()->flickr)) {
                    require_once(dirname(__FILE__) . '/external/flickr_api.php');
                    $flickr = new \Flickr([
                        'api_key' => \Idno\Core\site()->config()->flickr['apiKey'],
                        'api_secret' => \Idno\Core\site()->config()->flickr['secret']
                    ]);
                    if ($this->hasFlickr()) {
                        $flickr->token = \Idno\Core\site()->session()->currentUser()->flickr['access_token'];
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
                if (\Idno\Core\site()->session()->currentUser()->flickr) {
                    return true;
                }
                return false;
            }

        }

    }