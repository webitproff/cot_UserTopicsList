<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
  ==================== */
  
defined('COT_CODE') or die('Wrong URL');
// Environment setup
define('COT_USERTOPICSLIST', TRUE);
$env['location'] = 'usertopicslist';

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('plug', 'usertopicslist');

if(!$usr['auth_write']){
	if($usr['id'] == 0){
		$id = cot_import('id', 'G', 'INT');
		cot_redirect(cot_url('login', 'redirect='.base64_encode(cot_url('usertopicslist', 'id='.$id)), '', true));
	}else{
		cot_block(cot_auth('usertopicslist', 'any'));
	}
}  
  
//defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once (cot_langfile('usertopicslist'));
//require_once cot_incfile('usertopicslist', 'plug');
/* if (empty($id) && cot::$usr['id'] == 0) {
	cot_redirect(cot_url('any-ext', true));
} */

$out['subtitle'] = $L['usertopicslist_meta_title'];

$skin = cot_tplfile('usertopicslist', 'plug');
$user_posts = new XTemplate($skin);

$ajax = false;
if (empty($id) && empty($urr['user_id'])) {
	$id = cot_import('id', 'G', 'INT');
    if ($id > 0) $urr['user_id'] = $id;
	$ajax = true;
}

if (empty($id) && cot::$usr['id'] > 0) {
	$id = cot::$usr['id'];
}

$disable = false;
if ($urr['user_id'] != $id) {
	$sql = cot::$db->query("SELECT user_id FROM $db_users WHERE user_id='$id' LIMIT 1");
	if ($sql->rowCount() == 0) {
		$disable = true;
	} else {
		$urr['user_id'] = $id;
	}
}

if ($cot_modules['forums'] && !$disable) {
	require_once cot_incfile('forums', 'module');

	list($pnf, $df, $df_url) = cot_import_pagenav('df', cot::$cfg['plugin']['usertopicslist']['countonpage']);

	$totalitems = cot::$db->query("SELECT COUNT(*) FROM  $db_forum_topics t	WHERE ft_firstposterid='".
                                  $urr['user_id']."' AND t.ft_id")->fetchColumn();

	if (cot::$cfg['plugin']['usertopicslist']['ajax']) {
		$upf_ajax_begin = "<div id='reloadf'>";
		$upf_ajax_end = "</div>";
	}

	$pagenav = cot_pagenav('usertopicslist', "f=$df", $df, $totalitems, Cot::$cfg['plugin']['usertopicslist']['countonpage'],
                       'df', '', Cot::$cfg['plugin']['usertopicslist']['ajax']);	

	$sqlusertopicslist = cot::$db->query("SELECT t.ft_preview, t.ft_updated, t.ft_title, t.ft_id, t.ft_cat
		 FROM $db_forum_topics t
		 WHERE ft_firstposterid='".$urr['user_id']."'
		-- GROUP BY t.ft_id
		 ORDER BY ft_updated DESC
		 LIMIT $df, ".cot::$cfg['plugin']['usertopicslist']['countonpageinlist']);

	if ($sqlusertopicslist->rowCount() == 0) {
		$user_posts->parse("USERTOPICSLIST.NONE");

    } else {
		$ii = 0;
		while ($row = $sqlusertopicslist->fetch()) {
			if (cot_auth('forums', $row['ft_cat'], 'R')) {
				$ii++;
				$build_forum = cot_breadcrumbs(cot_forums_buildpath($row['ft_cat'], false), false, false);
                //------ Added by Alex ---------
                // Выдержка с поста
                $len_cut = 140;  // Длина выдержки с поста (символов)
                $row['ft_preview'] = cot_parse($row['ft_preview'], cot::$cfg['forums']["markup"]);
                // Убираем HTML теги:
                $row['ft_preview'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $row['ft_preview']);
                $row['ft_preview'] = cot_string_truncate($row['ft_preview'], $len_cut, true, false, '...');
                // /Выдержка с поста

				$user_posts->assign(array(
					"UPF_DATE" => cot_date('datetime_medium', $row['ft_updated']),
					"UPF_TOPIC" => $build_forum,
					"UPF_TOPIC_ID" => $row['ft_id'],
                    "UPF_TOPIC_TOPIC_ID" => $row['ft_id'],
					"UPF_TOPIC_TITLE" => htmlspecialchars($row['ft_title']),
                    "UPF_TOPIC_TEXT" => htmlspecialchars( $row['ft_preview']),
                    "UPF_TOPIC_POST_URL" => cot_url('forums', array('m'=>'posts', 'q'=>$row['ft_id'])),
					"UPF_NUM" => $ii,
					"UPF_ODDEVEN" => cot_build_oddeven($ii),
				));
				$user_posts->parse("USERTOPICSLIST.YES.TOPIC");
			}
		}

		$user_posts->assign(array(
			"UPF_AJAX_BEGIN" => $upf_ajax_begin,
			"UPF_AJAX_END" => $upf_ajax_end,
			"UPF_PAGENAV" => $pagenav['main'],
			"UPF_PAGENAV_PREV" => $pagenav['prev'],
			"UPF_PAGENAV_NEXT" => $pagenav['next'],
			"UPF_TOTALITEMS" => $totalitems,
			"UPF_COUNT_ON_PAGE" => $ii,
		));
		$user_posts->parse("USERTOPICSLIST.YES");
	}
} else {
	$user_posts->parse("USERTOPICSLIST.NONE");
}

$user_posts->parse("USERTOPICSLIST");
$user_pos = $user_posts->text("USERTOPICSLIST");


require_once $cfg['system_dir'].'/header.php';
	if (!defined('COT_USERTOPICSLIST')) {
		$t->assign(array("USERTOPICSLIST" => $user_pos));
	} else {
		cot_sendheaders();
		echo $user_pos;
	}
require_once $cfg['system_dir'].'/footer.php';