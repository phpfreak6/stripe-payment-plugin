<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">
<style>
.manage-column {
  width: 100% !important;
}
.edit_link {
  margin: 0 10px 0 0;
}
.msg {
  float: left;
  margin-left: 20px;
  margin-top: 10px;
  width: 95%;
}
.tablenav-pages-navspan {
  background: #f7f7f7 none repeat scroll 0 0;
  border-color: #ddd;
  color: #a0a5aa;
  height: 28px;
}
</style>
<div class="wrap">

<h1>View All Stripe Buttons</h1>

<div style="float:left; width:100%;">

	<a href="<?php echo admin_url();?>admin.php?page=add_stripeButton" class="btn btn-primary btn_submit">ADD NEW PAYMENT</a>

</div>
<?php
error_reporting(0);
$paged = @($_GET['paged']) ? $_GET['paged'] : 1;

$post_per_page = intval(20);

$offset = ($paged - 1)*$post_per_page;

$q = "SELECT * FROM " . $wpdb->prefix . "stripe_payment_tbl";

$p= " LIMIT ".$offset.", ".$post_per_page;

$subscriptions = $wpdb->get_results($q.''.$p,OBJECT);
$sql_posts_total = count( $wpdb->get_results($q,OBJECT) );

$max_num_pages = ceil($sql_posts_total / $post_per_page);

$curl = 'admin.php?'.$_SERVER['QUERY_STRING'];

$curl = explode("&paged=",$curl);

$curl = $curl[0];

				
		if($_GET['m'] == 'del'){
			
			$delete_record = $wpdb->delete( $table_name, array( 
			'ID' => $_GET['id']
			));
			if($delete_record){
				
				?>
				<script>
				window.location.href="admin.php?page=view_payment&dmsg=1";
				</script>
				<?php
				
					$success_msg = true;
				
				
			}else{
		
				$error_msg = true;
				
			}
		}

	if($_GET['msg'] == 1){
			
	echo '<div class="alert alert-success msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		Thanks Your Settings are saved!.</div>';
			
	}elseif($_GET['dmsg'] == 1){
		echo '<div class="alert alert-success msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		Record Delete Successfully !</div>';
	}
	elseif($error_msg){
		echo '<div class="alert alert-danger msg"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something Went Wrong.</div>';
	}
?>
<h2 class="screen-reader-text">Filter pages list</h2>

<div class="tablenav top">

	<div class="tablenav-pages">

		<span class="displaying-num"><?php echo $sql_posts_total;?> items</span>

		<span class="pagination-links">

			<?php

			echo "<a href='".$curl."&paged=1' class='prev-page'><span class='screen-reader-text'>Previous page</span><span aria-hidden='true'>&laquo;</span></a> "; 

			for ($i=1; $i<=$max_num_pages; $i++) { 

				if($i == $paged || ($i == 1 && empty($paged))){

					echo "<span class='tablenav-pages-navspan'  aria-hidden='true'>".$i."</span> ";

				}else

				{

					echo "<a href='".$curl."&paged=".$i."'>".$i."</a> "; 

				}										

			}; 

			echo "<a href='".$curl."&paged=$max_num_pages' class='next-page'><span class='screen-reader-text'>Next page</span><span aria-hidden='true'>&raquo;</span></a> "; 

			?>

		</span>

	</div>

	<br class="clear">

</div>




<form method="post" id="posts-filter">
<table class="wp-list-table widefat fixed striped pages">

<thead>

	<tr>

		<th class="manage-column column-author" scope="col">Sr.no</th>

		<th class="manage-column column-author" scope="col">Name</th>

		<th class="manage-column column-author" scope="col">Shortcode</th>

		<th class="manage-column column-author" scope="col">Status</th>

		<th class="manage-column column-author" scope="col">Action</th>

	</tr>

</thead>

<tbody id="the-list">

<?php

if(count($subscriptions)>0){
	$counter = $offset+1;
	foreach($subscriptions as $sub){
	// echo '<pre>'; print_r($sub);

		?>

		<tr>

			<td>#<?php echo $counter; ?></td>

			<td><?php echo $sub->name;?></td>

			<td><?php echo  '[stripePayment id="'.$sub->id .'"]' ;?></td>

			<td><?php echo $sub->status; ?></td>

			<td>

				
				<a href="admin.php?page=add_stripeButton&did=<?php echo $sub->id; ?>" class="edit_link">Edit</a>							
				<a href="admin.php?page=view_payment&did&m=del&id=<?php echo $sub->id; ?>" class="lit_cancel cancelSub">Delete</a>
				<a href="admin.php?page=add_stripeButton&copy=<?php echo $sub->id; ?>" class="lit_cancel" style="margin-left:10px;">Copy</a>
				
			</td>

		</tr>

		<?php
	$counter++ ;
	}

}else

{

	?><tr><td colspan="10"><b>Result Not Found.</b></td></tr>

	<?php

}

?>

</tbody>

<tfoot>

	<tr>

		<th class="manage-column column-author" scope="col">Sr.no</th>

		<th class="manage-column column-author" scope="col">Name</th>

		<th class="manage-column column-author" scope="col">Shortcode</th>

		<th class="manage-column column-author" scope="col">Status</th>

		<th class="manage-column column-author" scope="col">Action</th>

	</tr>

</tfoot>

</table>

</form>





<div class="tablenav top">

	<h2 class="screen-reader-text">Users list navigation</h2>

	<div class="tablenav-pages">

		<span class="displaying-num"><?php echo $sql_posts_total;?> items</span>

		<span class="pagination-links">

			<?php			

			echo "<a href='".$curl."&paged=1' class='prev-page'><span class='screen-reader-text'>Previous page</span><span aria-hidden='true'>&laquo;</span></a> "; 

			for ($i=1; $i<=$max_num_pages; $i++) { 

				if($i == $paged || ($i == 1 && empty($paged))){

					echo "<span class='tablenav-pages-navspan'  aria-hidden='true'>".$i."</span> ";

				}else

				{

					echo "<a href='".$curl."&paged=".$i."'>".$i."</a> "; 

				}										

			}; 

			echo "<a href='".$curl."&paged=$max_num_pages' class='next-page'><span class='screen-reader-text'>Next page</span><span aria-hidden='true'>&raquo;</span></a> "; 

			?>

		</span>

	</div>

	<br class="clear">

</div>

</div>
