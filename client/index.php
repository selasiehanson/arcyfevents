<style type="text/css">
	.events_container .datebox {
		background: #EEE;
		width: 110px;
		padding: 5px;
		/*margin-left: 630px;*/
		border: 1px solid #DDD;
		font-weight: bold;
		color: #333;
		font-size: 11px;
	}

	.events_container .pull-left {
		float: left;
	}

	.events_container .pull-right {
		float: right;
	}
	.events_container .clear {
		overflow: hidden;
	}

	.events_container .page_nav {
		margin-left: 40px;
		margin-top: 40px;
	}

	.events_container .page_nav .page-numbers {
		border: 1px solid #BBB;
		padding: 5px 10px;
		font-family: Arial;
		font-size: 11px;
	}

	.page_nav .current {
		background: #DDD;
		color: black;
		font-weight: bold;
	}
</style>
<div class="page_content events_container">
	<!-- <h2>Events</h2> -->
	<?php 
		//global $wp_query;
		$temp = $wp_query;
    	$wp_query = null;
		$wp_query = new WP_Query();
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$wp_query->query('showposts=10&post_type=event&post_status=publish'.'&paged='.$paged);
        while ($wp_query->have_posts()) {
           	$wp_query->the_post();  
           	$custom = get_post_custom($wp_query->ID);
        	$startDate = $custom["startDate"][0];
        	$startDate = split('[, ]', $startDate);
           ?>
            <div class="article">
            <div class="clear">
            	<div class="pull-left">
            		<h5>
						<a href="<?php  the_permalink(); ?> "> <?php the_title(); ?> </a> 
					</h5>
            	</div>
				<div class='datebox pull-right'>
					<?php 
						 echo substr($startDate[0], 0,3).", ". $startDate[2] ." "; 
						 echo substr($startDate[3], 0,3).  ", ". $startDate[4];
					?>
				</div>
            </div> 
        	<div>
				<?php 
					the_excerpt(); 
				?>
			</div>
				
			</div>
             
        <?php 
        }
        
	    $total_pages = $wp_query->max_num_pages;

	    if ($total_pages > 1){
	      $current_page = max(1, get_query_var('paged'));
	      echo '<div class="page_nav">';
	      echo paginate_links(array(
	          'base' => get_pagenum_link(1) . '%_%',
	          'format' => '/page/%#%',
	          'current' => $current_page,
	          'total' => $total_pages,
	          'prev_text' => 'Prev',
	          'next_text' => 'Next'
	        ));

	      echo '</div>';

	    }

        $wp_query = $temp;
	?>

</div>

