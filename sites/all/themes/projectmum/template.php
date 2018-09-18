<?php

/**
 * @file
 * The primary PHP file for this theme.
 */
function _convert_date(&$field,$index,$vfield,$format) {
	$d = strtotime($field[LANGUAGE_NONE][$index][$vfield].' '.$field[LANGUAGE_NONE][$index]['timezone_db']);
	return format_date($d,'custom',$format,$field[LANGUAGE_NONE][$index]['timezone']);
}

function projectmum_helper_process_page(&$variables) {
	if(isset($variables['node']) && $variables['node']->type == 'event') {
		$variables['title'] = NULL;
	}
}

function _get_map_markup(&$location,$height=450,$width=600){
	$api_key = variable_get('gmap_api_key','');
	$lat = $location['latitude'];
	$long = $location['longitude'];
	return '<iframe width="800" height="600" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=' . $lat . '%2C' . $long . '&key=' . $api_key . '" allowfullscreen></iframe>';
}

function _get_event_time(&$node) {
  $start_min = _convert_date($node->field_date,0,'value','i');
  $start_format = $start_min == '00'?'ga':'g:ia';
  $end_min = _convert_date($node->field_date,0,'value2','i');
  $end_format = $end_min == '00'?'ga':'g:ia';
  
  return _convert_date($node->field_date,0,'value',$start_format) . ' - ' . _convert_date($node->field_date,0,'value2',$end_format);
}

function _get_djs(&$node) {
  $djs = array();
	
  if (isset($node->field_dj_set[LANGUAGE_NONE])) {
	$ids = array();
	foreach ($node->field_dj_set[LANGUAGE_NONE] as $key => $value) {
		$ids[] = $value['value'];
	}
	$result = paragraphs_item_load_multiple($ids);
	foreach($result as $key => $value) {
		//dpm($value);
		$cid = $value->field_dj[LANGUAGE_NONE][0]['target_id'];
		$c_node = node_load($cid);
		$dj = array(
				'name' => $c_node->title,
				'time' =>  _convert_date($value->field_date,0,'value','g:ia') . ' - ' . _convert_date($value->field_date,0,'value2','g:ia'),
				'links' => array()
		);
		
		$num_links = isset($c_node->field_link[LANGUAGE_NONE])?count($c_node->field_link[LANGUAGE_NONE]):0;
		for ($i = 0; $i < $num_links; $i++) {
			$dj['links'][] = array(
					'title' => $c_node->field_link[LANGUAGE_NONE][$i]['title'],
					'url' => $c_node->field_link[LANGUAGE_NONE][$i]['url'],
			);
		}
		$djs[] = $dj;
		
	}
  }
	
  return $djs;
	
}

function _get_contributor(&$node,$field) {
  $c_data = array();
  
  if (isset($node->{$field}[LANGUAGE_NONE])) {
		
	$ctype_map = _get_vocab_map('contributor_type');
	$c_nodes = array();
	$cids = array();
	for ($i = 0; $i < count($node->{$field}[LANGUAGE_NONE]); $i++) {
	  $cids[] = $node->{$field}[LANGUAGE_NONE][$i]['target_id'];
	}
	
	$c_nodes = node_load_multiple($cids);

	$i = 0;
	foreach ($c_nodes as $nid => $c_node) {
	  $c_data[$i] = array(
	  	'name' =>  $c_node->title
	  );
	  
	  if (isset($c_node->field_logo[LANGUAGE_NONE])) {
	    $c_data[$i]['logo'] = file_create_url($c_node->field_logo[LANGUAGE_NONE][0]['uri']);
	  }	
	  
	  $num_links = isset($c_node->field_link[LANGUAGE_NONE])?count($c_node->field_link[LANGUAGE_NONE]):0;
	  for ($j = 0; $j < $num_links; $j++) {
	  	$c_data[$i]['links'][$j] = array(
	  	  'title' => $c_node->field_link[LANGUAGE_NONE][$j]['title'],
	  	  'url' => $c_node->field_link[LANGUAGE_NONE][$j]['url'],
	  	);
	  }
	  $i++;
	}
  }
  
  return $c_data;
}

function _get_sponsors(&$node) {
  $sponsors = array(
    'org' => array(),
  	'business' => array()	
  );
	
  if (isset($node->field_sponsor[LANGUAGE_NONE])) {
  	  
	$ctype_map = _get_vocab_map('contributor_type');
		
	foreach ($node->field_sponsor[LANGUAGE_NONE] as $key => $value) {
		$ids[] = $value['target_id'];
	}
	$c_nodes = node_load_multiple($ids);
	
	foreach ($c_nodes as $id => $c_node) {
		$sponsor = array(
				'name' => $c_node->title,
				'url' => $c_node->field_link[LANGUAGE_NONE][0]['url'],
				'logo' => file_create_url($c_node->field_logo[LANGUAGE_NONE][0]['uri'])
		);
		
		if ($c_node->field_contributor_type[LANGUAGE_NONE][0]['tid'] == $ctype_map['Organization']) {
			$sponsors['org'][] = $sponsor;
		}
		elseif ($c_node->field_contributor_type[LANGUAGE_NONE][0]['tid'] == $ctype_map['Business']) {
			$sponsors['business'][] = $sponsor;
		}
	}
  }	

  return $sponsors;
	
}

function _get_vocab_map($name){
	$map = array();
	$vocab = taxonomy_vocabulary_machine_name_load($name);
	$vocab_terms = taxonomy_get_tree($vocab->vid, $parent = 0, NULL, TRUE);
	$options = array();
	
	foreach ($vocab_terms as $t) {
		$map[strval($t->name)] = $t->tid;
	}
	
	return $map;
	
}

function projectmum_preprocess_page(array &$variables) {
  $variables['navbar_classes_array'][] = 'container-fluid';
  $index = array_search('container', $variables['navbar_classes_array']);
  if ($index !== FALSE) {
  	unset($variables['navbar_classes_array'][$index]);
  }
}