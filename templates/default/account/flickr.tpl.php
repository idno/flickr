<div class="row">

    <div class="span10 offset1">
	            <?=$this->draw('account/menu')?>
        <h1>Flickr</h1>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="/account/flickr/" class="form-horizontal" method="post">
            <?php
                if (empty(\Idno\Core\site()->session()->currentUser()->flickr)) {
            ?>
                    <div class="control-group">
                        <div class="controls-config">
	                       <div class="row">
						   		<div class="span6">
                                                            <p>
                                Easily share pictures to Flickr.</p>  
                                
                                <p>
                                With Flickr connected, you can cross-post images that you publish publicly on your site. 
                            </p>
						   		</div>
	                       </div>
	                       	                        <div class="social span4">
                            <p>
                                <a href="<?=$vars['login_url']?>" class="connect fl">Connect Flickr</a>
                            </p>
	                       	                        </div>
                        </div>
                    </div>
                <?php

                } else {

                    ?>
                    <div class="control-group">
                        <div class="controls-config">
	                      <div class="row">
						    <div class="span6">
                            <p>
                                Your account is currently connected to Flickr. Public content that you post here
                                can be shared with your Flickr account.
                            </p>
						    </div>
	                      </div>
                            <p>
                                <input type="hidden" name="remove" value="1" />
                                <button type="submit" class="btn btn-large btn-primary">Disconnect Flickr.</button>
                            </p>
                        </div>
                    </div>

                <?php

                }
            ?>
            <?= \Idno\Core\site()->actions()->signForm('/account/flickr/')?>
        </form>
    </div>
</div>
