<?php

class AclHelper extends AppHelper {
	
	
	/**
	 * Renders the markup for a permissions table
	 */
	public function renderTable( $acos ){
		
		//return nothing if the acos array is empty
		if( ! is_array( $acos ) ){
			return;
		}
		
		//create an empty array for group IDs
		$groups = $gids = array();
		
		//figure out the group data and store it
		foreach($acos as $aco){
			foreach( $aco['Groups'] as $group ){
				$gids[$group['Group']['id']] = $group['Group']['id'];
				$groups[$group['Group']['id']] = $group;
			}
		}
		
?>
		<div class="permissions_table_wrapper">
			<div class="permissions_table">
<?php
				$this->renderGroupList( $groups );
				foreach( $acos as $aco ){
					$this->renderColumn( $aco, $gids );
				}
?>
			</div>
		</div>
<?php
	}
	
	
	/**
	 * renders a column for the group list (labels)
	 */
	public function renderGroupList( $groups ){
				
		//return nothing if the groups array is empty
		if( !is_array( $groups ) ) {
			return;
		}
?>
		<div class="groups_column">
			<?php foreach( $groups as $group ){ ?>
				<div class="group group_<?php echo $group['Group']['id']; ?>">
					<?php echo $group['Group']['title']; ?>
				</div>
			<?php } ?>
		</div>
<?php
	
	}
	
	
	/**
	 * renders an aco and its children. a depth can be set
	 */
	public function renderColumn( $aco, $gids = null, $depth = 0, $parent_id = null, $level = 0 ){
?>
		<div id="aco_column_<?php echo $aco['Aco']['id']; ?>" class="aco_column level_<?php echo $level; ?> <?php echo ( ! is_null($parent_id ) ) ? 'child_column parent_aco_column_' . $parent_id : '' ; ?>">
			<div class="heading aco_<?php echo $aco['Aco']['id']; ?>">
				<h3><?php echo $aco['Aco']['alias']; ?></h3>
				<?php if( isset( $aco['Children'] ) && is_array( $aco['Children'] ) && count( $aco['Children'] ) > 0){ ?>
					<a href="#aco_column_<?php echo $aco['Aco']['id']; ?>">Refine</a>
				<?php } ?>
			</div>
<?php
			if(isset( $aco['Groups'] ) && $gids != null){
				
				//id a single id is given put it into an array
				if( ! is_array( $gids ) ){
					$gids = array( $gids );
				};
				
				//loop through the group id(s) requested				
				foreach( $gids as $gid ){
					
					//findout if the aco shares the group id requested
					foreach( $aco['Groups'] as $group ){
						if( $gid == $group['Group']['id'] ){

							//create a unique id for this slide
							$sid = 'ps-' . $group['Group']['id'] . $aco['Aco']['id'];

							//render the group info
?>
							<div id="<?php echo $sid; ?>" class="permissions_slider group_<?php echo $group['Group']['id']; ?> aco_<?php echo $aco['Aco']['id']; ?>">

								<label for="slider-allow-<?php echo $sid; ?>">Allow</label>
								<input id="slider-allow-<?php echo $sid; ?>" class="allow" type="radio" name="slider" value="1" <?php echo ( $group['Group']['canRead'] == 1 ) ? 'checked' : '' ; ?> />

								<label for="slider-block-<?php echo $sid; ?>">Block</label>
								<input id="slider-block-<?php echo $sid; ?>" class="block" type="radio" name="slider" value="-1" <?php echo (  $group['Group']['canRead'] == 0 ) ? 'checked' : '' ; ?> />

								<?php if( true ){ //will be if the setting has a parent ?>
									<label for="slider-inherit-<?php echo $sid; ?>">Default</label>
									<input id="slider-inherit-<?php echo $group['Group']['id']; ?>" class="inherit" type="radio" name="slider" value="0" <?php echo (  $group['Group']['canRead'] == 2 ) ? 'checked' : '' ; ?> />
								<?php } ?>
							</div>
							<script>
								$( '#<?php echo $sid; ?>' ).complexSlider({
									//'parent': <?php //secho ( $lsid ) ? '"#' . $lsid . '"' : 'null' ; ?> //will be the id of the parent
								});
							</script>
<?php
							//$lsid = $sid;
							//end the search for a matching group
							break;
						}
					}
				}
			}
?>
		</div>
<?php 
		
		//if the aco has children aco(s)
		if( isset( $aco['Children'] ) ){
			foreach( $aco['Children'] as $child_aco ){
				
				//iterate the level
				$level += 1;
		
		
				// | 		  !!!KEY!!!  		|
				// | 							|
				// | 0 = till the end			|
				// | 1 = break					|
				// | # = break after # cycles	|
				
				
				//if the depth is above 1 or is 0.
				if( $depth > 1 || $depth == 0 ){
					$this->renderColumn( $child_aco, $gids, $depth, $aco['Aco']['id'], $level );
				}
				
				//break if depth is 1;
				else{
					break;
				}
				
				//iterate the depth
				$depth -= 1;
			}
		}
	}	
}