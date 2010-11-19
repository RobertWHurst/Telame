<?php
// Additional ACL
class AaclComponent extends Object {
	var $components = array('Acl');

	function initialize(&$controller) {
		$this->controller = $controller;
	}

	// $uid is who owns what's being looked at
	// $fid is who is trying to view it
	// $what is 'profile' or 'media' etc
	function checkPermissions($uid, $fid, $what) {
		if ($uid == $fid) {
			return true;
		}

		// load the arosaco model if not already
		if (!isset($this->controller->ArosAco)) {
			$this->controller->loadModel('ArosAco');
		}

		// find the root for the user who we're trying to look at
		$this->Acl->Aco->recursive = -1;
		$rootAco = $this->Acl->Aco->find('first', array(
			'conditions' => array(
				'alias' => 'User::' . $uid,
			)
		));

		// if 'what' is numeric then it is an id of what we should check
		// get the aco of what we want
		if (!is_numeric($what)) {
			$this->Acl->Aco->recursive = -1;
			$aco = $this->Acl->Aco->find('first', array(
				'conditions' => array(
					'lft >' => $rootAco['Aco']['lft'],
					'rght <' => $rootAco['Aco']['rght'],
					'alias' => $what,
				)
			));
		} else {
			$this->Acl->Aco->recursive = -1;
			$aco = $this->Acl->Aco->find('first', array(
				'conditions' => array(
					'lft >' => $rootAco['Aco']['lft'],
					'rght <' => $rootAco['Aco']['rght'],
					'id' => $what,
				)
			));
		}

		// that aco doesn't exist, return false
		if (!$aco) {
			return false;
		}

		// find the aro of the user who wants to see the object
		$this->Acl->Aro->recursive = -1;
		$userAro = $this->Acl->Aro->find('first', array(
			'conditions' => array(
				'model' => 'User',
				'foreign_key' => $fid,
			)
		));

		// get specific permissions for that user
		$this->controller->ArosAco->recursive = -1;
		$arosAco = $this->controller->ArosAco->find('first', array(
			'conditions' => array(
				'aro_id' => $userAro['Aro']['id'],
				'aco_id' => $aco['Aco']['id'],
			)
		));

		// nothing specific, try the group perms
		if (!$arosAco) {
			// if we don't have GroupsUser available, load it
			if (!isset($this->controller->GroupsUser)) {
				$this->controller->loadModel('GroupsUser');
			}

			// find the group the friend is in for the user
			$groups = $this->controller->GroupsUser->listGroups($uid, $fid);

			// no group, deny
			if (!isset($groups['GroupsUser']['group_id'])) {
				return false;
			}

			// find the group aro
			$this->Acl->Aro->recursive = -1;
			$groupsAro = $this->Acl->Aro->find('first', array(
				'conditions' => array(
					'model' => 'Group',
					'foreign_key' => $groups['GroupsUser']['group_id'],
				)
			));

			// no group aro (should never happen) deny access
			if (!$groupsAro) {
				return false;
			}

			// find the perms for the group
			$this->controller->ArosAco->recursive = -1;
			$arosAco = $this->controller->ArosAco->find('first', array(
				'conditions' => array(
					'aro_id' => $groupsAro['Aro']['id'],
					'aco_id' => $aco['Aco']['id'],
				)
			));

			// no perms set, deny
			if (!$arosAco) {
				return false;
			}
		}

		// do we block, allow, or inherit
		switch ($arosAco['ArosAco']['_read']) {
			// block
			case 0: {
				return false;
				break;
			}
			// allow)
			case 1: {
				return true;
				break;
			}
			// inherit
			case 2: {
				// we pass the aco's (what we want to look at) parent id, then next time we already know the parent
				// return the value between calls, it will return all the way back down
				return $this->checkPermissions($uid, $fid, $aco['Aco']['parent_id']);
			}
			// something else, deny
			default: {
				return false;
			}
		}
	}

	// We take the User ID, their groups, and optionally a parent to start
	// With the info we build an array of ACO's and their children and what groups can do what with them
	function getAcoTree($uid, $groups = null, $parent = null) {
		if (!isset($this->controller->ArosAco)) {
			$this->controller->loadModel('ArosAco');
		}
		// If the parent is null, we need they UID, this is the starting point
		if (is_null($parent)) {
			// Find the user's lowest level ACO, and only get the ID, it's all we need
			$parent = $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $uid)));
		} else {
			$parent = $this->Acl->Aco->find('first', array('conditions' => array('id' => $parent['Aco']['id'])));
		}

		if (is_null($groups)) {
			App::Import('Model', 'Group');
			$this->Group = new Group();
			$groups = $this->Group->getFriendLists(array('uid' => $uid));
		}

		// Find all the direct children, only direct
		$children = $this->Acl->Aco->children($parent['Aco']['id'], true);

		// now loop through all the kids
		foreach ($children as $key => $child) {
			$i = 0;

			// also the groups (this could potentially be a LOT of looping, may need tweaking)
			foreach ($groups as $group) {
				$this->Acl->Aro->recursive = -1;
				$aro = $this->Acl->Aro->find('first', array(
					'conditions' => array(
						'model' => 'Group',
						'foreign_key' => $group['Group']['id'],
					)
				));
				// loop throug the groups pulled from the db
				$arosAco = $this->controller->ArosAco->find('first', array(
					'conditions' => array(
						'aco_id' => $child['Aco']['id'],
						'aro_id' => $aro['Aro']['id'],
					)
				));

				// set the permission
				$children[$key]['Groups'][$i] = $group;
				$children[$key]['Groups'][$i++]['Group']['canRead'] = ($arosAco['ArosAco']['_read'] == -1 ? 0 : $arosAco['ArosAco']['_read']);
			}
			// check if this child has kids
			if ($this->hasChildren($child)) {
				// Yes? well then get the grandkids, and if required great grandkids, etc
				$children[$key]['Children'] = $this->getAcoTree($uid, $groups, $child);
			}
		}
		// return once we've check it all
		return $children;
	}

	// takes an ACO parent and checks if it's got kids or not
	function hasChildren($parent) {
		// If the left and right are x and x+1, then there is no kids
		if ($parent['Aco']['rght'] == $parent['Aco']['lft'] +1 ) {
			return false;
		} else {
			return true;
		}
	}

	function saveAco($data, $parent = null) {
		if (!isset($this->controller->ArosAco)) {
			$this->controller->loadModel('ArosAco');
		}
		// we can't use the security tokens here, so remove them
		if (is_null($parent)) {
			unset($data['_Token']);
		}

		$uid = $this->controller->currentUser['User']['id'];
		foreach ($data as $alias => $groups) {
			foreach ($groups as $group => $perm) {

				if (!is_array($perm)) {
					$gid = explode('_', $group);
					$gid = $gid[1];

					$this->Acl->Aro->recursive = -1;
					$aro = $this->Acl->Aro->find('first', array(
						'conditions' => array(
							'model' => 'Group',
							'foreign_key' => $gid,
						)
					));

					$this->Acl->Aco->recursive = -1;
					$aco = $this->Acl->Aco->find('first', array(
						'conditions' => array(
							'alias' => 'User::' . $this->controller->currentUser['User']['id']
						)
					));

					$aco = $this->Acl->Aco->find('first', array(
						'conditions' => array(
							'lft >' => $aco['Aco']['lft'],
							'rght <' => $aco['Aco']['rght'],
							'alias' => $alias,
						)
					));

					$arosAco = $this->controller->ArosAco->find('first', array('conditions' => array(
						'aro_id' => $aro['Aro']['id'],
						'aco_id' => $aco['Aco']['id'],
					)));

					$arosAco['ArosAco']['_read'] = $perm;
					$arosAco['ArosAco']['aro_id'] = $aro['Aro']['id'];
					$arosAco['ArosAco']['aco_id'] = $aco['Aco']['id'];

					$this->controller->ArosAco->id = $arosAco['ArosAco']['id'];
					$this->controller->ArosAco->save($arosAco);

				} else {
					$perm = array($group => $perm);
					// first is array of data, second is parent name
					$this->saveAco($perm, $parent . '/' . $alias);
				}
			}
		}
		return true;
	}

	function createAcl($uid = null, $root = null, $acls = null) {
		// the user_id is already in the aco table
		if (is_null($root) && $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $uid)))) {
			return false;
		}

		if (is_null($acls)) {
			$node = $this->Acl->Aco->node('Users');
			$parentId = Set::extract($node, "0.Aco.id");

			$this->Acl->Aco->create(array('parent_id' => $parentId, 'alias' => 'User::' . $uid));
			$this->Acl->Aco->save();

			$node = $this->Acl->Aco->node('User::' . $uid);
			$parentId = Set::extract($node, "0.Aco.id");

			$acls = Configure::read('UserAcls');
		} else {
			$node = $this->Acl->Aco->node($root);
			$parentId = Set::extract($node, '0.Aco.id');
		}

		foreach ($acls as $key => $val) {
			if (is_array($val)) {
				$this->Acl->Aco->create(array('parent_id' => $parentId, 'alias' => $key));
				$this->Acl->Aco->save();
				$this->createAcl($uid, $key, $val);
			} else {
				$this->Acl->Aco->create(array('parent_id' => $parentId, 'alias' => $val));
				$this->Acl->Aco->save();
			}
		}
		return true;
	}

	function deleteAcoTree($uid, $groups) {
		$aco = $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $uid)));
		$this->Acl->Aco->delete($aco['Aco']['id']);

		$this->controller->loadModel('ArosAco');


		foreach ($groups as $group) {
			$aro = $this->Acl->Aro->find('first', array('conditions' => array('model' => 'Group', 'foreign_key' => $group['Group']['id'])));
			// i think this is automatically deleted
			$this->Acl->Aro->delete($aro['Aro']['id']);

			$arosAcos = $this->controller->ArosAco->find('all', array('conditions' => array('aro_id' => $aro['Aro']['id'])));
			foreach ($arosAcos as $arosAco) {
				$this->controller->ArosAco->delete($arosAco['ArosAco']['id']);
			}
		}
	}
}



