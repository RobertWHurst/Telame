<?php
class AaclBehavior extends ModelBehavior {

	function setup(&$model, $config = array()) {
	}

	function afterSave(&$model) {
	return;
		$rootPerms = array();

		// we need the acl component to do the checking
		App::import('Component', 'Acl');
		$model->Acl = new AclComponent();

		// find the root user
		$model->Acl->Aco->recursive = -1;
		$aco = $model->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $model->data[$model->alias]['author_id'])));

		// find the sub
		$model->Acl->Aco->recursive = 1;
		$aco = $model->Acl->Aco->find('first', array(
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
			$model->Acl->Aco->create($acoData);
			$model->Acl->Aco->save();

			foreach ($passedPerm as $key => $val) {
//				$aroAco = array(
//					'ArosAco' => array(
//						'aro_id' => $key,
//						'aco_id' => $model->Acl->Aco->id;
//						'_read' => $val;
//					)
//				);
			}
		}


	}

	function afterDelete(&$model) {
	}

	function beforeFind(&$model, $data) {
		if (!$model->aid) {
			return $data;
		}

// FIXME
return $data;

		App::import('Component', 'Acl');
		$model->Acl = new AclComponent();

		// make it an array if it's not already
		if (!is_array($model->aid)) {
			$model->aid = array($model->aid);
		}

		// remove ourself from the author id array
		$key = array_search($model->currentUserId, $model->aid);
		if($key !== false) {
			unset($model->aid[$key]);
		}

		foreach ($model->aid as $aid) {
			// This is query for a reason.  If I use the built in queries, the contain methods are not used.
			// So it will return EVERYTHING related to the user.
			$group = $model->query('SELECT group_id FROM groups_users WHERE user_id = ' . $model->currentUserId . ' AND friend_id = ' . $aid);

			// get the user
			$model->Acl->Aro->recursive = -1;
			$aro = $model->Acl->Aro->find('first', array(
				'conditions' => array(
					'model' => 'User',
					'foreign_key' => $aid,
				)
			));

			// find the root author user
			$model->Acl->Aco->recursive = -1;
			$rootUserAco = $model->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $aid)));

			// model aco id
			$model->Acl->Aco->recursive = 1;
			$rootAco = $model->Acl->Aco->find('first', array(
				'conditions' => array(
					'alias' => $model->alias,
					'lft >' => $rootUserAco['Aco']['lft'],
					'rght <' => $rootUserAco['Aco']['rght'],
				)
			));


			// if left is one less than right, they have no specific permissions
			// FIXME: this needs finishing
			if ($rootAco['Aco']['lft'] != ($rootAco['Aco']['rght'] + 1)) {
				$model->Acl->Aco->recursive = 1;
				$Acos = $model->Acl->Aco->find('all', array(
					'conditions' => array(
						'lft >' => $rootAco['Aco']['lft'],
						'rght <' => $rootAco['Aco']['rght'],
					)
				));
			}

			// permissions the user has set for all wall posts
			$rootPermissions = Set::extract('/Aro[foreign_key=' . $group[0][0]['group_id'] . ']/Permission/_read', $rootAco);

			// if there is no root permissions, or the permission[0] is false (ie, the user is not allowed to view)
			if(empty($rootPermissions) || !$rootPermissions[0]) {
				// find the key of the author and remove them from the list
				$key = array_search($aid, $data['conditions']['OR'][$model->alias . '.author_id']);
				unset($data['conditions']['OR'][$model->alias . '.author_id'][$key]);
			}
		}
		return $data;
	}

}
