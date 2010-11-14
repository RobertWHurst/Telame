<?php

class AclViewHelper extends AppHelper {

    public $helpers = array( 'Form', 'Hrl' );
	
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
				<?php $this->renderGroupList( $groups ); ?>
				<div class="cell_scroll_area_wrapper" style="height: <?php echo 42 + ( 43 * count( $groups ) ); ?>px;">
					<div class="cell_scroll_area" style="width: <?php echo 151 * count( $acos ); ?>px;">
<?php
						foreach( $acos as $aco ){
							$this->renderColumn( $aco, $gids );
						}
?>
					</div>
				</div>
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
		<div id="aco_column_<?php echo $aco['Aco']['id']; ?>" class="aco_column level_<?php echo $level; ?> <?php echo ( ! is_null($parent_id ) ) ? 'parent_aco_column_' . $parent_id : '' ; ?>">
			<div class="heading aco_<?php echo $aco['Aco']['id']; ?>">
				<?php if( isset( $aco['Children'] ) && is_array( $aco['Children'] ) && count( $aco['Children'] ) > 0){ ?>
					<a href="#children_of_aco_column_<?php echo $aco['Aco']['id']; ?>">Refine</a>
				<?php } ?>
				<h3><?php echo $aco['Aco']['alias']; ?></h3>
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
							<div class="cell group_<?php echo $group['Group']['id']; ?> aco_<?php echo $aco['Aco']['id']; ?>">

								<div id="<?php echo $sid; ?>" class="slider">
<?php
									echo $this->Form->input(
										(!is_null($parent_id) ? $parent_id . '.' : '') . $aco['Aco']['alias'] . '.Group_' . $group['Group']['id'],
										array(
											'type' => 'radio',
											'default' => $group['Group']['canRead'],
											'options' => array(
												'0' => __('block', true),
												'1' => __('allow', true),
												'2' => __('inherit', true)
											),
											'legend' => false
										)
									);
?>
								</div>
							</div>
<?php
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