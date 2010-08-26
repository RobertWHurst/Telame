<?php
class AaclComponent extends Object {
	var $components = array('Acl');

	// $uid is who owns what's being looked at
	// $fid is who is trying to view it
	// $what is 'profile' or 'media' etc
	function checkPermissions($uid, $fid, $what) {
		$aco = 'User::' . $uid . '/' . $what;
		if (!@$this->Acl->check(array('model' => 'User', 'foreign_key' => $fid), $aco, 'read')) {
			App::Import('Model', 'GroupsUser');
			$this->GroupsUser = new GroupsUser();
			
			$groups = $this->GroupsUser->listGroups($uid, $fid);
			if (!isset($groups['GroupsUser']['group_id'])) {
				return false;
			}
			if (!@$this->Acl->check(array('model' => 'Group', 'foreign_key' => $groups['GroupsUser']['group_id']), $aco, 'read')) {
				return false;
			}
		}
		return true;
	}

	function getAcoTree($uid, $groups, $parent = null) {
		if (is_null($parent)) {
			$parent = $this->Acl->Aco->find('first', array('conditions' => array('alias' => 'User::' . $uid), 'fields' => 'id'));
		} else {
			$parent = $this->Acl->Aco->find('first', array('conditions' => array('id' => $parent['Aco']['id']), 'fields' => 'id'));
		}
		$children = $this->Acl->Aco->children($parent['Aco']['id'], true);

		foreach ($children as $key => $child) {
			foreach ($groups as $group) {
				$children[$key]['Group'][$group['Group']['id']] = @$this->Acl->check(array('model' => 'Group', 'foreign_key' => $group['Group']['id']),  $child['Aco']['alias'], 'read');
			}

			if ($this->hasChildren($child)) {
				$children[$key]['Children'] = $this->getAcoTree($uid, $groups, $child);
			}
		}

		return $children;
	}


	function hasChildren($parent) {
		if ($parent['Aco']['rght'] == $parent['Aco']['lft'] +1 ) {
			return false;
		} else {
			return true;
		}
	}
}