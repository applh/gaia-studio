<em>XP Meta Box</em>
<?php 

print_r($post); 
echo "<br>";
print_r(get_post_meta($post->ID));
?>
<script>
    console.log('meta_box_content');
    console.log(<?php echo json_encode($post); ?>);
</script>