<?php
/************************************************************************/
/* ATutor																*/
/************************************************************************/
/* Copyright (c) 2002-2010                                              */
/* http://atutor.ca                                                     */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

if (!defined('AT_INCLUDE_PATH') || !defined('AT_UPGRADE_INCLUDE_PATH')) { exit; }

function concat_config_values($config_name) {
    $row = queryDB('SELECT value FROM %sconfig WHERE name = "%s"', array($_POST['step1']['tb_prefix'], $config_name));
    $value = $row['value'];
    $row = queryDB('SELECT value FROM %sconfig WHERE name = "%s_2"', array($_POST['step1']['tb_prefix'], $config_name));
    if(!empty($row))
    {
        $value2 = $row['value'];
        $value  = $value.$value2;
        $result = queryDB('UPDATE %sconfig SET value = %s WHERE name = "%s"', array($_POST['step1']['tb_prefix'], $value, $config_name));
        $result = queryDB('DELETE FROM %sconfig WHERE name = "%s_2"', array($_POST['step1']['tb_prefix'], $config_name));
    }
}

$_POST['db_login']    = urldecode($_POST['db_login']);
$_POST['db_password'] = urldecode($_POST['db_password']);
/* Destory session */
session_unset();
$_SESSION= array();
if(isset($_POST['submit']) && ($_POST['action'] == 'process')) {
	unset($errors);
	$db = @mysql_connect($_POST['step1']['db_host'] . ':' . $_POST['step1']['db_port'], $_POST['step1']['db_login'], urldecode($_POST['step1']['db_password']));
	@mysql_select_db($_POST['step1']['db_name'], $db);

	if (version_compare($_POST['step1']['old_version'], '1.5', '<')) {
		$_POST['admin_username'] = trim($_POST['admin_username']);
		$_POST['admin_password'] = trim($_POST['admin_password']);
		$_POST['admin_email']    = trim($_POST['admin_email']);
		$_POST['site_name']      = trim($_POST['site_name']);
		$_POST['home_url']	     = trim($_POST['home_url']);

		/* Super Administrator Account checking: */
		if ($_POST['admin_username'] == ''){
			$errors[] = 'Administrator username cannot be empty.';
		} else {
			/* check for special characters */
			if (!(preg_match("/^[a-zA-Z0-9_]([a-zA-Z0-9_])*$/i", $_POST['admin_username']))){
				$errors[] = 'Administrator username is not valid.';
			}
		}
		if ($_POST['admin_password'] == '') {
			$errors[] = 'Administrator password cannot be empty.';
		}
		if ($_POST['admin_email'] == '') {
			$errors[] = 'Administrator email cannot be empty.';
		} else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $_POST['admin_email'])) {
			$errors[] = 'Administrator email is not valid.';
		}

		/* System Preferences checking: */
		if ($_POST['email'] == '') {
			$errors[] = 'Contact email cannot be empty.';
		} else if (!preg_match("/^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,6}$/i", $_POST['email'])) {
			$errors[] = 'Contact email is not valid.';
		}

		if (!isset($errors)) {
            $result = queryDB("INSERT INTO %sadmins VALUES ('%s, '%s', '', '%s', 'en', 1, NOW())", array($_POST['step1']['tb_prefix'], $_POST[admin_username], $_POST[admin_password], $_POST[admin_email]));

			unset($_POST['admin_username']);
			unset($_POST['admin_password']);
			unset($_POST['admin_email']);
		}
	}
	if (version_compare($_POST['step1']['old_version'], '1.5.2', '<')) {
		// update config table
        $result = queryDB("REPLACE INTO %sconfig VALUES ('contact_email', '%s')", array($_POST['step1']['tb_prefix'], urldecode($_POST['step1']['contact_email'])));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('email_notification', '%d')", array($_POST['step1']['tb_prefix'], ($_POST['step1']['email_notification'] ? 1 : 0)));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('allow_instructor_requests', '%d')", array($_POST['step1']['tb_prefix'], ($_POST['step1']['allow_instructor_requests'] ? 1 : 0)));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('auto_approve_instructors', '%d')", array($_POST['step1']['tb_prefix'], ($_POST['step1']['auto_approve'] ? 1 : 0)));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('max_file_size', '%d')", array($_POST['step1']['tb_prefix'], (int) $_POST['step1']['max_file_size']));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('max_course_size', '%d')", array($_POST['step1']['tb_prefix'], (int) $_POST['step1']['max_course_size']));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('max_course_float', '%d')", array($_POST['step1']['tb_prefix'], (int) $_POST['step1']['max_course_float']));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('illegal_extentions', '%s')", array($_POST['step1']['tb_prefix'], str_replace(',','|',urldecode($_POST['step1']['ill_ext']))));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('site_name', '%s')", array($_POST['step1']['tb_prefix'], urldecode($_POST['step1']['site_name'])));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('home_url', '%s')", array($_POST['step1']['tb_prefix'], urldecode($_POST['step1']['home_url'])));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('default_language', 'en')", array($_POST['step1']['tb_prefix']))

        $result = queryDB("REPLACE INTO %sconfig VALUES ('cache_dir', '%s')", array($_POST['step1']['tb_prefix'], urldecode($_POST['step1']['cache_dir'])));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('enable_category_themes', '%d')", array($_POST['step1']['tb_prefix'], ($_POST['step1']['theme_categories'] ? 1 : 0)));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('course_backups', '%d')", array($_POST['step1']['tb_prefix'], (int) $_POST['step1']['course_backups']));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('email_confirmation', '%d'", array($_POST['step1']['tb_prefix'], ($_POST['step1']['email_confirmation'] ? 1 : 0)));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('master_list', '%d')", array($_POST['step1']['tb_prefix'], ($_POST['step1']['master_list'] ? 1 : 0)));

        $result = queryDB("REPLACE INTO %sconfig VALUES ('enable_handbook_notes', '%d'", array($_POST['step1']['tb_prefix'], ($_POST['step1']['enable_handbook_notes'] ? 1 : 0)));

		// check for bits 8192 and 4096 and remove them if they're set.
        $result = queryDB("UPDATE %scourse_enrollment SET `privileges` = `privileges` - 8192 WHERE `privileges` & 8192", array($_POST['step1']['tb_prefix']));

        $result = queryDB("UPDATE %scourse_enrollment SET `privileges` = `privileges` - 4096 WHERE `privileges` & 4096", array($_POST['step1']['tb_prefix']));

	}

	if (version_compare($_POST['step1']['old_version'], '1.5.3', '<')) {
        $result = queryDB("DELETE FROM %sgroups", array($_POST['step1']['tb_prefix']));
        $result = queryDB("DELETE FROM %sgroups_members", array($_POST['step1']['tb_prefix']));
        $result = queryDB("DELETE FROM %stests_groups", array($_POST['step1']['tb_prefix']));
	}
	if (version_compare($_POST['step1']['old_version'], '1.5.3.3', '<')) {
		// set display_name_format to "login"
        $result = queryDB("INSERT INTO %sconfig VALUES ('display_name_format', '0')", array($_POST['step1']['tb_prefix']));
	}

	if (version_compare($_POST['step1']['old_version'], '1.5.4', '<')) {
		/* find all the multiple choice multiple answer questions and convert them to 
		 * Multiple Answer which is number 7.
		 */
        $result = queryDB("UPDATE %stests_questions SET type=7 WHERE type=1 AND answer_0 + answer_1 + answer_2 + answer_3 + answer_4 + answer_5 + answer_6 + answer_7 + answer_8 + answer_9 > 1", array($_POST['step1']['tb_prefix']));

        $row = queryDB("SELECT MAX(admin_privilege) AS max FROM %smodules", array($_POST['step1']['tb_prefix']));
		$priv = $row['max'] * 2;

        $result = queryDB("UPDATE %smodules SET `admin_privilege`=%d WHERE `dir_name`='_core/enrolment'", array($_POST['step1']['tb_prefix'], $priv));
	}
	if (version_compare($_POST['step1']['old_version'], '1.5.5', '<')) {
        $result = queryDB("UPDATE %stests_results SET status=1, date_taken=date_taken, end_time=date_taken", array($_POST['step1']['tb_prefix']));
	}
	if (version_compare($_POST['step1']['old_version'], '1.6.4', '<')) {
		/* convert all content nodes to the IMS standard. (adds null nodes for all top pages) */
		include('ustep_content_conversion.php');

		// fix all the wrong ordering
        $result = queryDB("SELECT content_id, content_parent_id, ordering, course_id FROM %scontent ORDER BY course_id, content_parent_id, ordering", array($_POST['step1']['tb_prefix']));
        foreach ($result as $row) {
			if ($current_course_id != $row['course_id']) {
				$current_course_id = $row['course_id'];
				unset($current_parent_id);
				unset($ordering);
			}
			if ($current_parent_id != $row['content_parent_id']) {
				$current_parent_id = $row['content_parent_id'];
				$ordering = 1;
			}

			if ($row['ordering'] != $ordering) {
                $result = queryDB("UPDATE %scontent SET ordering=%d WHERE content_id=%d", array($_POST['step1']['tb_prefix'], $ordering, $row[content_id]));
			}

			 echo "\n";
		
			$ordering++;
		}
		
		/* Convert db to a tree */
        $result_course = queryDB('SELECT distinct course_id FROM %scontent', array($_POST['step1']['tb_prefix']));
        foreach ($result_course as $row_course) {
            $result = queryDB('SELECT * FROM %scontent WHERE course_id=%d', array($_POST['step1']['tb_prefix'], $row_course['course_id']));
			$content_array = array(); 

            foreach ($result as $row){
                $content_array[$row['content_parent_id']][$row['ordering']] = $row['content_id'];
			}

			$tree = buildTree($content_array[0], $content_array);

			/* Restructure the tree */
			$tree = rebuild($tree);

			/* Update the Db based on this new tree */
			reconstruct($tree, '', 0, $_POST['step1']['tb_prefix']);
		}
	}

	/* deal with the extra modules: */
	/* for each module in the modules table check if that module still exists in the mod directory. */
	/* if that module does not exist then check the old directory and prompt to have it copied */
	/* or delete it from the modules table. or maybe disable it instead? */
	if (version_compare($_POST['step1']['old_version'], '1.5.1', '>')) {
		define('TABLE_PREFIX', $_POST['step1']['tb_prefix']);
		require(AT_INCLUDE_PATH . '../mods/_core/modules/classes/Module.class.php');
		$moduleFactory = new ModuleFactory(FALSE);
		$module_list =& $moduleFactory->getModules(AT_MODULE_STATUS_DISABLED | AT_MODULE_STATUS_ENABLED);
		$keys = array_keys($module_list);
		foreach($keys as $dir_name) {
			$module =& $module_list[$dir_name];
			$module->setIsMissing($module->isExtra());
		}
	}

	/* fixed the typo of "fuild" theme that was introduced in 1.6.1 : */
	if (version_compare($_POST['step1']['new_version'], '1.6.0', '>')) {
        $result = queryDB("UPDATE %sthemes
                   SET title='Fluid', dir_name='fluid'
		         WHERE dir_name='fuild'", array($_POST['step1']['tb_prefix']));

        $result = queryDB('UPDATE %sconfig
		           SET value=replace(value, \':"fuild";\', \':"fluid";\')
		         WHERE name=\'pref_defaults\'', array($_POST['step1']['tb_prefix']));

		$result = queryDB('UPDATE %smembers
		           SET preferences=replace(preferences, \':"fuild";\', \':"fluid";\')', array($_POST['step1']['tb_prefix']));
	}

	/* Saved the atutor installation path into "config" table after 2.0.2 */
	if (version_compare($_POST['step1']['new_version'], '2.0.2', '>')) {
		// Calculate the ATutor installation path and save into database for the usage of
		// session associated path @ include/vitals.inc.php
        $result = queryDB("INSERT INTO %sconfig VALUES ('session_path', '%s')", array($_POST['step1']['tb_prefix'], get_atutor_installation_path(AT_UPGRADE_INCLUDE_PATH)))
	}

	if (version_compare($_POST['step1']['new_version'], '2.1', '>')) {
		concat_config_values('main_defaults');

		concat_config_values('home_defaults');
	}

	if (!isset($errors)) {
		unset($errors);
		unset($_POST['submit']);
		unset($action);
		store_steps($step);
		$step++;
		return;
	}
}

print_progress($step);

if (isset($errors)) {
	print_errors($errors);
}


?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="action" value="process" />
	<input type="hidden" name="step" value="<?php echo $step; ?>" />
	<?php print_hidden($step); ?>

<?php if (version_compare($_POST['step1']['old_version'], '1.5', '<')): ?>
	<p>Below are new configuration options that are available for this version.</p>

	<br />
		<table width="70%" class="tableborder" cellspacing="0" cellpadding="1" align="center">
		<tr>
			<th colspan="2">Super Administrator</th>
		</tr>
		<tr>
			<td class="row1" colspan="2">The Super Administrator account is used for managing ATutor. Since ATutor version 1.5 the Super Administrator can also create additional Administrators each with their own privileges and roles.</td>
		</tr>
		<tr>
			<td class="row1"><span class="required" title="Required Field">*</span><b><label for="username">Administrator Username:</label></b><br />
			May contain only letters, numbers, or underscores.</td>
			<td class="row1"><input type="text" name="admin_username" id="username" maxlength="20" size="20" value="<?php if (!empty($_POST['admin_username'])) { echo $stripslashes(htmlspecialchars($_POST['admin_username'])); } else { echo urldecode($_POST['step1']['admin_username']); } ?>" class="formfield" /></td>
		</tr>
		<tr>
			<td class="row1"><span class="required" title="Required Field">*</span><b><label for="password">Administrator Password:</label></b></td>
			<td class="row1"><input type="text" name="admin_password" id="password" maxlength="15" size="15" value="<?php if (!empty($_POST['admin_password'])) { echo $stripslashes(htmlspecialchars($_POST['admin_password'])); } else { echo urldecode($_POST['step1']['admin_password']); } ?>" class="formfield" /></td>
		</tr>
		<tr>
			<td class="row1"><span class="required" title="Required Field">*</span><b><label for="email">Administrator Email:</label></b></td>
			<td class="row1"><input type="text" name="admin_email" id="email" size="30" value="<?php if (!empty($_POST['admin_email'])) { echo $stripslashes(htmlspecialchars($_POST['admin_email'])); } else { echo urldecode($_POST['step1']['admin_email']); } ?>" class="formfield" /></td>
		</tr>
		</table>

		<br />

		<table width="70%" class="tableborder" cellspacing="0" cellpadding="1" align="center">
		<tr>
			<th colspan="2">System Preferences</th>
		</tr>
		<tr>
			<td class="row1"><span class="required" title="Required Field">*</span><b><label for="cemail">Contact Email:</label></b><br />
			The email that will be used as the return email when needed and when instructor account requests are made.</td>
			<td class="row1"><input type="text" name="email" id="cemail" size="30" value="<?php if (!empty($_POST['email'])) { echo $stripslashes(htmlspecialchars($_POST['admin_email'])); } else { echo urldecode($_POST['step1']['admin_email']); } ?>" class="formfield" /></td>
		</tr>
		</table>
<?php endif; ?>
<?php if (version_compare($_POST['step1']['old_version'], '1.5.3', '<')): ?>
	<p>Groups made prior to 1.5.3 are not backwards compatible and will be removed.</p>
<?php else: ?>
	<p>There are no new configuration options for this version.</p>
<?php endif; ?>

	<br />
	<br />
	<div align="center"><input type="submit" class="button" value=" Next &raquo;" name="submit" /></div>
</form>