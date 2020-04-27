<div class="ryzom-ui ryzom-ui-header">
		<div class="ryzom-ui-tl">
			<div class="ryzom-ui-tr">
				<div class="ryzom-ui-t">Boss timers</div>
			</div>
		</div>
		<div class="ryzom-ui-l">
			<div class="ryzom-ui-r">
				<div class="ryzom-ui-m">
					<div class="ryzom-ui-body">
						<h1>Boss timers</h1>
						<form method="POST">
							 <table class="ryzom-ui">
								  <tr>
										<td>
											 <table>
												  <tr>
												      <td>
												         Min Level:
												      </td>
												      <td>
												          <select name="minLevel" id="minLevel" class="ryzom-ui-select">
												              <?php for ($i = 270; $i > 0; $i = $i - 10){
												                  $selected = $i == $minLevel ? 'selected' : '';
												              ?>
												              <option <?= $selected ?> value="<?= $i ?>"> <?= $i ?>  </option>
												              <?php } ?>
												          </select>        
												      </td>
												      <td>
												        <button type="submit" class="ryzom-ui-button">Filter</button>  
												      </td>
												      <td>
												          &nbsp;&nbsp;
												      </td>
												      <td>
												          Default sort:
												      </td>
												      <td>
												          <select name="sort">
												              <option <?= $sort == 'name' ? 'selected' : '' ?> value="name">
												              	Name
												              </option>
												              <option <?= $sort == 'region' ? 'selected' : '' ?> value="region">
												              	Region
												              </option>
												              <option <?= $sort == 'level' ? 'selected' : '' ?> value="level">
												              	Level
												              	</option>
												              <option <?= $sort == 'time' ? 'selected' : '' ?> value="time">
												              	Time
												              	</option>
												          </select>
												      </td>
												      <td>
												          <button type="submit" name="setSort" class="ryzom-ui-button">
												          	Submit
												          </button>
												      </td>
												  </tr>
											 </table>
											 </form>
										</td>
										<td>
										</td>
								  <tr>
										<td>
											<form method="POST" action="<?= $isIngame ? '?' . htmlspecialchars(SID) : ''?>">
											<input type="hidden" name="redirect" value="<?= $_SERVER['PHP_SELF'] ?>">
											 <table cellspacing="0" cellpadding="3" class="ryzom-ui">
												  <tr bgcolor="black" align="center">
												      <th>
												      	<a href="?sort=name<?= $sid ?>" class="ryzom-ui-button">
												      	Name
												      	</a>
												      </th>
												      <th><a href="?sort=region<?= $sid ?>" class="ryzom-ui-button">Region</a></th>
												      <th><a href="?sort=level<?= $sid ?>" class="ryzom-ui-button">Level</a></th>
												      <th colspan="2" align="center">
												      	<a href="?sort=time<?= $sid ?>" class="ryzom-ui-button">Last kill</a>
												      </th>
												  </tr>
												  <?php 
												  $i = 0;
												  foreach($bosses as $key => $row){ 
												      
												      $bg = '';
												      if($i++ % 2 != 0){
												          $bg = 'bgcolor="#222222"';
												      }
												  ?>
												      <tr <?= $bg ?>>
												          <td><?= $row['name'] ?></td>
												          <td><?= $row['region'] ?></td>
												          <td align="center"><?php echo $row['level'] ?></td>
												          <td align="right" style="padding: 0 10px 0 10px">
												          	<?= timeDiff($row['time']) ?>
												          </td>
												          <td style="padding: 0 5px 0 10px">
												              <button type="submit" name="kill" value="<?= $row['id'] ?>">Kill</button>
												          </td>
												      </tr>
												  <?php } ?>
											 </table>
											 </form>
										</td>
										<td valign="top">
											 <table cellspacing="0" cellpadding="3">
												  <tr bgcolor="black" style="color: white" align="center">
												      <th>
												          Spawn locations
												      </th>
												  </tr>
													<?php foreach($spawns as $name => $image){
														 $bg = '';
														 if($i++ % 2 != 0){
															$bg = 'bgcolor="#222222"';
														 }
													?>
												  <tr <?= $bg ?>>
												      <td>
												          <a href="?spawn=<?= urlencode($name) . $sid?>">
												              <?= $name ?>
												          </a>
												      </td>
												  </tr>
											 <?php }?>
											 </table>
										</td>
								  </tr>
							 </table>
						</form>
					
					
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
