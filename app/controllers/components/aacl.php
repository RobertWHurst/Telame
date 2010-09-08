<?php
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
		// what ACO we're checking
		$aco = 'User::' . $uid . '/' . $what;
		// First check the user for over ridden perms, they come first
		if (!@$this->Acl->check(array('model' => 'User', 'foreign_key' => $fid), $aco, 'read')) {
			// Ok, so nothing special, import the GroupsUser model
			App::Import('Model', 'GroupsUser');
			$this->GroupsUser = new GroupsUser();

			// Now, get all the user's groups
			$groups = $this->GroupsUser->listGroups($uid, $fid);
			// if the user isn't in a group (should only happen if they're not friends) then return false
			if (!isset($groups['GroupsUser']['group_id'])) {
				return false;
			}
			// All looks good, get checkin
			if (!@$this->Acl->check(array('model' => 'Group', 'foreign_key' => $groups['GroupsUser']['group_id']), $aco, 'read')) {
				return false;
			}
		}
		// I guess this might be considered a "flaw" because it defaults to true, but the other checks *should* always return true/false
		return true;
	}

	// We take the User ID, their groups, and optionally a parent to start
	// With the info we build an array of ACO's and their children and what groups can do what with them
	function getAcoTree($uid, $groups = null, $parent = null) {
		// If the parent is null, we need they UID, this is the starting point
		if (is_null($parent)) {
			// Find the user's lowest level ACO, and only get the ID, it's all we need
			$parent = $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $uid), 'fields' => 'id'));
		} else {
			$parent = $this->Acl->Aco->find('first', array('conditions' => array('id' => $parent['Aco']['id']), 'fields' => 'id'));
		}
		
		if (is_null($groups)) {
			App::Import('Model', 'Group');
			$this->Group = new Group();
			$groups = $this->Group->getFriendLists(0, 0, array('uid' => $uid));
		}
		// Find all the direct children, only direct
		$children = $this->Acl->Aco->children($parent['Aco']['id'], true);

		// now loop through all the kids
		foreach ($children as $key => $child) {
			$i = 0;
			// also the groups (this could potentially be a LOT of looping, may need tweaking)
			foreach ($groups as $group) {
				// Check if the group is allowed to read the current ACO, and store the result in the $child[$key]['Groups']['id'] array
				// Use the @ to suppress error messages.
				// FIXME: Remove the @, this will only work if/when all the groups are stored in the db for the user's acos
				$result = @$this->Acl->check(array('model' => 'Group', 'foreign_key' => $group['Group']['id']),  $child['Aco']['alias'], 'read');
				$children[$key]['Groups'][$i] = $group;
				$children[$key]['Groups'][$i++]['Group']['canRead'] = ($result ? 1 : 0);
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
	
	function saveAco($data) {
		// we can't use the security tokens here, so remove them
		unset($data['_Token']);
		$uid = $this->controller->currentUser['User']['id'];
		foreach ($data as $acoKey => $acoVal) {
			foreach ($acoVal as $group => $perm) {
				if (!is_array($perm)) {
					$gid = explode('_', $group);
					// can read
					if ($perm) {
						$this->Acl->allow('Group.' . $gid[1], 'User::' . $uid . '/' . $acoKey, 'read');
					} else {
						$this->Acl->deny('Group.' . $gid[1], 'User::' . $uid . '/' . $acoKey, 'read');
					}
				}
			}
		}
		exit;
		return true;
	}
}



