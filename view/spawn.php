<div class="ryzom-ui ryzom-ui-header">
		<div class="ryzom-ui-tl">
			<div class="ryzom-ui-tr">
				<div class="ryzom-ui-t"><?= $title ?></div>
			</div>
		</div>
		<div class="ryzom-ui-l">
			<div class="ryzom-ui-r">
				<div class="ryzom-ui-m">
					<div class="ryzom-ui-body">
                        <br>
                        <a href="/" class="ryzom-ui-button">Back</a>&nbsp;
                        <?php foreach($config['spawnLocations'] as $name => $image){?>
                        	<a href="?spawn=<?= urlencode($name) . $sid?>" class="ryzom-ui-button"><?= $name ?></a>&nbsp;
                        <?php }?>
                        <br>
                        <br>
                        <img src="<?= $src ?>">
					</div>
				</div>
			</div>
		</div>

		<div class="ryzom-ui-bl">
			<div class="ryzom-ui-br">
				<div class="ryzom-ui-b"></div>
			</div>
		</div>
		<p class="ryzom-ui-notice">powered by <a class="ryzom-ui-notice" href="https://api.ryzom.com/">ryzom-api</a></p>
</div>
