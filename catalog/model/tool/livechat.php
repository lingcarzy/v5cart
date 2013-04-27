<?php

class ModelToolLiveChat extends Model {
	public function getCode() {
		$query = $this->db->query("SELECT * FROM @@livechat WHERE status=1 ORDER BY listorder ASC,type,chatid DESC");
		$html = '<ul>';
		foreach($query->rows as $row) {
			if ($row['label']) {
				$html .= "<li class='tit'>" . html_entity_decode($row['label']) ."</li>";
			}
			$image = '';
			if ($row['image'] && file_exists(DIR_IMAGE . $row['image'])) {
				$image = "<img src='" . HTTP_IMAGE . $row['image'] . "' border='0'>";
			}			
			switch($row['type']) {
				case 'MSN':
					if (!$image) {
						if ($row['skin'])
						$image = "<img border='0' src='" . HTTP_IMAGE . 'livechat/' . $row['skin'] ."' />";
						else $image = $row['name'];
					}
					$html .= "<li><a href='msnim:chat?contact={$row['code']}' target='_blank' rel='nofollow' title='MSN: {$row['name']}'>{$image}</a>";
					if ($row['ifhide']) $html .= "<span>{$row['name']}</span>";
					$html .="</li>";
				break;
				case 'YMSG':
					if (!$image) {
						if (is_numeric($row['skin']))
						$image = "<img border='0' src='http://opi.yahoo.com/online?u={$row['code']}&m=g&t={$row['skin']}&l=us' />";
						else $image = $row['name'];
					}
					$html .= "<li><a href='ymsgr:sendim?{$row['code']}' target='_blank' rel='nofollow' title='Yahoo! Messenger: {$row['name']}'>{$image}</a>";
					if ($row['ifhide']) $html .= "<span>{$row['name']}</span>";
					$html .="</li>";
				break;
				case 'SKYPE':
					if (!$image) {
						if (in_array($row['skin'], array('smallicon', 'smallclassic', 'mediumicon', 'bigclassic', 'balloon')))
						$image = "<img border='0' src='http://mystatus.skype.com/{$row['skin']}/{$row['code']}' />";
						else $image = $row['name'];
					}
					$html .= "<li><a href='skype:{$row['code']}?call' target='_blank' rel='nofollow' title='Skype IM: {$row['name']}'>{$image}</a>";
					if ($row['ifhide']) $html .= "<span>{$row['name']}</span>";
					$html .="</li>";
				break;
				case 'TEL':	
					if (!$image) {
						if ($row['skin'])
						$image = "<img border='0' src='" . HTTP_IMAGE . 'livechat/' . $row['skin'] ."' />";
					}
					$html .= "<li>" . ($image ? $image.' ' : '') ."{$row['code']}";
					if ($row['ifhide']) $html .= "<span>{$row['name']}</span>";
					$html .="</li>";
				break;
				default:
					$row['code'] = html_entity_decode($row['code']);
					$html .= "<li>{$row['code']}</li>";
			}
		}
		$html .= "</ul>";
		return $html;
	}
}