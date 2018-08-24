<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup templates
 */
?>

<?php 
//dpm($node);
$djs = _get_djs($node);
$sound = _get_contributor($node,'field_sound');
$visuals = _get_contributor($node,'field_visuals');
$sponsors = _get_sponsors($node);
$producers = _get_contributor($node,'field_producer');
$webmaster = _get_contributor($node,'field_webmaster');

?>

<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

<!-- EVENT HEADER -->
<section id="event-header">
  <div class="panel panel-default">
    <div class="panel-heading">
	  <h2 class="text-center">
	    <div class="subtitle">Project MUM <?php print _convert_date($node->field_date,0,'value','Y') ?></div>
        <div class="event-title"><a href="<?php print $node->field_link[LANGUAGE_NONE][0]['url']; ?>"><?php print $node->title ?></a></div>
	  </h2>
	</div>
	
	<div class="panel-body">
	    <h2 class="text-center">
	    <div class="event-date"><?php print _convert_date($node->field_date,0,'value','F j Y'); ?></div>
        <div class="event-time"><?php print _convert_date($node->field_date,0,'value','ga') . ' - ' . _convert_date($node->field_date,0,'value2','ga'); ?></div>
		<?php if (isset($node->field_donation) && $node->field_donation[LANGUAGE_NONE][0]['value'] > 0) { ?>
	    <div class="donation">Suggested Donation $<?php print $node->field_donation[LANGUAGE_NONE][0]['value']; ?></div>			
	    <?php } ?> 
	    </h2>   
	</div>
  </div>
</section>

<!-- MAP -->
<section id="event-map">
  <div class="panel panel-default">
    <div class="panel-heading">
	</div>
	
	<div class="panel-body text-center">
	  <?php print _get_map_markup($node->location); ?>
	  <div class="event-description">
	  <?php print $node->body[LANGUAGE_NONE][0]['value']; ?>
	  </div>
	</div>
	
  </div>
</section>

<!-- DJs -->
<?php if (count($djs)) { ?>
<section id="event-djs" >
  <div class="panel panel-default">				
    <div class="panel-heading">
	  <h2>DJs</h2>
	</div>
	
	<div class="panel-body">
	<?php foreach ($djs as $dj) { ?>
	  <div class="dj">	
	    <h2><?php print $dj['time'] . ' ' . $dj['name']?></h2>
	    <?php foreach ($dj['links'] as $link) { ?>
	  	  <div class="dj-link"><a href="<?php print $link['url']?>" target="_blank" ><?php print $link['title']?></a></div> 
	    <?php } ?>
	  </div>	  
	<?php } ?>
         	
      <h2>Sound: <a href="<?php print $sound[0]['links'][0]['url']; ?>"><?php print $sound[0]['name']; ?></a></h2>
      <h2>Visuals: <a href="<?php print $visuals[0]['links'][0]['url']; ?>"><?php print $visuals[0]['name']; ?></a></h2>
      
    </div>
  </div>
</section>
<?php } ?>

<!-- SPONSORS -->
<?php if (count($sponsors['org']) || count($sponsors['business'])) { ?>
<section id="event-sponsors">
  <div class="panel panel-default">
    <div class="panel-heading">
	    <h2 class="text-center" >Special thanks to:</h2>
	  </div>
	  
	  <div class="panel-body">
	  
 	  <?php if (count($sponsors['org'])) { 
	  $col = 'col-lg-' . floor(12/count($sponsors['org'])); ?>
	  <div class="container">
	    <div class="row">
        	  
	      <?php foreach($sponsors['org'] as $org) { ?>
	  	  <div class="<?php print $col;?>"><a href="<?php print $org['url']?>" target="_blank" alt="<?php print $org['name'] ?>" ><img class="img-responsive" src="<?php print $org['logo'];?>" /></a></div>
	      <?php } ?>
	    </div>
	  </div>		
	  <?php } ?>
	
 	  <?php if (count($sponsors['business'])) { 
	  $col = 'col-lg-' . floor(12/count($sponsors['business'])); ?>
	  <div class="container">
	    <div class="row">
	      <h2 class="text-center">Support your Local Businesses!</h2>		
		</div>
	  
	    <div class="row">
	      <?php foreach($sponsors['business'] as $business) { ?>
	  	  <div class="<?php print $col;?>"><a href="<?php print $business['url']?>" target="_blank" alt="<?php print $business['name'] ?>" ><img class="img-responsive" src="<?php print $business['logo'];?>" /></a></div>
	      <?php } ?>
	    </div>
	  </div>		
	  <?php } ?>
	</div>	
</section>
<?php } ?>

<!-- PRODUCERS -->
<?php if (count($producers)) { ?>
<section id="event-producers">
  <div class="panel panel-default">
    <div class="panel-heading">
	  <h2 class="text-center" >Project MUM <?php print _convert_date($node->field_date,0,'value','Y') ?> Producers</h2>
	</div>
	  
	<div class="panel-body">
	  
 	  <?php $col = 'col-lg-' . floor(12/count($producers)); ?>
	  <div class="container">
	    <div class="row">
	  
	    <?php foreach($producers as $p) { 
	  	  print '<h3 class="' . $col . '">' . $p['name'] . '</h3>'; 
	    }
	    ?>
	    </div>
	  </div>			

	</div>
	
	<div class="panel-footer">
	  <div class="row">
	    <?php if (count($webmaster)) { ?>
	    <h3 class="text-center">
	      <div>Website Wizardry</div>
	      <div><?php print $webmaster[0]['name']; ?></div>
	    </h3>
	    <?php } ?>
	  </div>
	</div>
	
  </div>	
</section>
<?php } ?>


</article>
