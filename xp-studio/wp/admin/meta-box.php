<em>XP Meta Box</em>
<br/>
<textarea cols="160" rows="20">
<?php 

print_r($post); 
echo "\n\n";
print_r(get_post_meta($post->ID));
?>
</textarea>
<script>
    console.log('meta_box_content');
    console.log(<?php echo json_encode($post); ?>);
</script>