<?php
class AaclBehavior extends ModelBehavior {

	function setup(&$model, $config = array()) {
		$model->Aco = ClassRegistry::init('Aco');
		$model->Aro = ClassRegistry::init('Aro');
		$model->ArosAco = ClassRegistry::init('ArosAco');
	}

	function afterSave(&$model) {
		$rootPerms = array();

		// find the root user
		$model->Aco->recursive = -1;
		$aco = $model->Aco->find('first', array('conditions' => array('alias' => 'User::' . $model->data[$model->alias]['author_id'])));

		// find the sub
		$model->Aco->recursive = 1;
		$aco = $model->Aco->find('first', array(
			'conditions' => array(
				'alias' => $model->alias,
				'lft >' => $aco['Aco']['lft'],
				'rght <' => $aco['Aco']['rght'],
			)
		));

		// loops over each groups, and sets rootperm[group_id] to the current perm set.
		// will be used to compare against what perm has been passed
		foreach ($aco['Aro'] as $perm) {
			$permission = ($perm['Permission']['_read'] ? 1 : 0);
			$rootPerms[$perm['foreign_key']] = $permission;
		}

		// sort out what's been passed for comparison
		$passedPerms = array();
		// flip the keys and vals, we only care about the key after the flip
		$model->data[$model->alias]['Group'] = array_flip($model->data[$model->alias]['Group']);
		// loop over all the root perms
		foreach ($rootPerms as $key => $val) {
			// if the key exists in the passed data (then they have checked the box) then set perm to yes
			if (array_key_exists($key, $model->data[$model->alias]['Group'])) {
				$passedPerms[$key] = '1';
			} // otherwise say no
			else {
				$passedPerms[$key] = '0';
			}
		}

		// if the passed perms are different than the root, we need to do some aco saving
		if ($passedPerms != $rootPerms) {
			$acoData = array(
				'parent_id' => $aco['Aco']['id'],
				'alias' => $model->alias . '::' . $model->id,
				'model' => $model->alias,
				'foreign_key' => $model->id,
			);
			$model->Aco->create($acoData);
			$model->Aco->save();

			foreach ($passedPerms as $key => $val) {
				$aroAco['ArosAco'] = array(
					'aro_id' => $key,
					'aco_id' => $model->Aco->id,
					'_create' => 0,
					'_read' => $val,
					'_update' => 0,
					'_delete' => 0,
				);
				$model->ArosAco->create();
				$model->ArosAco->save($aroAco);
			}
		}

	}

	// Clean up the DB after a delete
	function afterDelete(&$model) {
		$model->Aco->recursive = -1;
		$aco = $model->Aco->find('first', array('conditions' => array('alias' => $model->alias . '::' . $model->id)));
		if ($aco) {
			$model->ArosAco->deleteAll(array('aco_id' => $aco['Aco']['id']));
			$model->Aco->delete($aco['Aco']['id']);
		}
	}

	function beforeFind(&$model, $data) {
		if (!$model->friends) {
			// If we don't have an array of friends, extract the user_id from the conditions
			$model->friends = array($data['conditions'][$model->alias . '.user_id']);
		}
		// make it an array if it's not already
		if (!is_array($model->friends)) {
			$model->friends = array($model->friends);
		}

		// remove ourself from the author id array
		$key = array_search($model->currentUserId, $model->friends);
		if($key !== false) {
			unset($model->friends[$key]);
		}

		foreach ($model->friends as $fid) {
			// This is query for a reason.  If I use the built in queries, the contain methods are not used.
			// So it will return EVERYTHING related to the user.
			$group = $model->query('SELECT group_id FROM groups_users WHERE user_id = ' . $model->currentUserId . ' AND friend_id = ' . $fid . ' LIMIT 1');

			// get the user
			$model->Aro->recursive = -1;
			$aro = $model->Aro->find('first', array(
				'conditions' => array(
					'model' => 'User',
					'foreign_key' => $fid,
				)
			));

			// find the root author user
			$model->Aco->recursive = -1;
			$rootUserAco = $model->Aco->find('first', array('conditions' => array('alias' => 'User::' . $fid)));

			// model aco id
			$model->Aco->recursive = 1;
			$rootAco = $model->Aco->find('first', array(
				'conditions' => array(
					'alias' => $model->alias,
					'lft >' => $rootUserAco['Aco']['lft'],
					'rght <' => $rootUserAco['Aco']['rght'],
				)
			));


			// if left is one less than right, they have no specific permissions
			// This block does specific permissions
			if ($rootAco['Aco']['lft'] != ($rootAco['Aco']['rght'] + 1)) {
				$model->Aco->recursive = -1;
				$acos = $model->Aco->find('all', array(
					'conditions' => array(
						'lft >' => $rootAco['Aco']['lft'],
						'rght <' => $rootAco['Aco']['rght'],
					)
				));
				foreach ($acos as $aco) {
					$aroAco = $model->ArosAco->find('first', array(
						'conditions' => array(
							'aro_id' => $group[0][0]['group_id'],
							'aco_id' => $aco['Aco']['id']
						),
						'fields' => '_read',
					));
					if (!$aroAco['ArosAco']['_read']) {
						$data['conditions']['NOT'][$model->alias . '.id'][] = $aco['Aco']['foreign_key'];
					} else {
						$data['conditions']['AND']['OR'][$model->alias . '.id'][] = $aco['Aco']['foreign_key'];
					}
				}
			}


			// permissions the user has set for all items
			$rootPermissions = Set::extract('/Aro[foreign_key=' . $group[0][0]['group_id'] . ']/Permission/_read', $rootAco);

			// if there is no root permissions, or the permission[0] is false (ie, the user is not allowed to view)
			if(empty($rootPermissions) || !$rootPermissions[0]) {
				// find the key of the author and remove them from the list
				$key = array_search($fid, $data['conditions']['AND']['OR'][$model->alias . '.author_id']);
				unset($data['conditions']['AND']['OR'][$model->alias . '.author_id'][$key]);
			}
		}

		return $data;
	}

}
