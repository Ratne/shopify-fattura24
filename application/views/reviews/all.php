<?php /* printR( $data ) ; */ ?>
<style>
.rating-box {
  background: #eee none repeat scroll 0 0;
  border: 1px solid #333;
  border-radius: 3px;
  float: left;
  margin: 10px 0;
  padding: 5px 8px;
  width: 100%;
}
.rating-box h2 {
  margin: 0;
}
.rating > img {
  padding: 0 2px;
  width: 1.5%;
}
.review {
  color: #666;
}
</style>
<?php
foreach( $data->reviews as $key_a => $val_a )
{
	// printR($val_a);
	if( isset( $val_a->service ) )
	{
		echo "<div class='rating-box'>" ;
			echo "<div class='title'><h2>" . $val_a->service->title . "</h2></div>" ;
			echo "<div class='rating'>";
				for( $a=0; $a<5; $a++ )
				{
					echo "<img src='" . base_url( "images/" . ( ( $a < $val_a->service->rating->rating ) ? 1 : 0 ) . ".png" ) . "' />" ;
				}
			echo "</div>" ;
			echo "<div class='review'>" . $val_a->service->review . "</div>" ;
		echo "</div>" ;
	}
}
?>