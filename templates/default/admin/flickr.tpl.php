<div class="row">

    <div class="span10 offset1">
	            <?=$this->draw('admin/menu')?>
        <h1>Flickr</h1>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="<?=\Idno\Core\site()->config()->getURL()?>admin/flickr/" class="form-horizontal" method="post">
            <div class="control-group">
                <div class="controls">
                    <p>
                        To begin using Flickr, <a href="http://www.flickr.com/services/apps/" target="_blank">create a new application in
                            the Flickr apps portal</a>.</p>
                    <p>
                        You need to edit the authentication flow and set the callback URL to:<br />
                        <input type="text" class="span4" value="<?=\Idno\Core\site()->config()->url . 'flickr/callback'?>" />
                    </p>
                    <p>
                        Once you've finished, fill in the details below. You can then <a href="<?=\Idno\Core\site()->config()->getURL()?>account/flickr/">connect your Flickr account</a>.
                    </p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">API Key</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="API Key" class="span4" name="apiKey" value="<?=htmlspecialchars(\Idno\Core\site()->config()->flickr['apiKey'])?>" >
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">App secret</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="App secret" class="span4" name="secret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->flickr['secret'])?>" >
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/flickr/')?>
        </form>
    </div>
</div>
