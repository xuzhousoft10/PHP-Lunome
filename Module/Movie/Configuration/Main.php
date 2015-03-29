<?php
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
/**
 * 
 */
return array(
'movie_character_photo_types' => array('png'=>'image/png','gif'=>'image/gif','jpg'=>'image/jpeg'),
'movie_character_photo_max_size' => 1024*1024,
'movie_mark_styles' => array(Movie::MARK_UNMARKED=>'warning',Movie::MARK_INTERESTED=>'success',Movie::MARK_WATCHED=>'info',Movie::MARK_IGNORED=>'default'),
'movie_beg_message' => '怀着各种复杂与激动的心情， 我来到了这里， 我抬头， 望了望天，想起了你，此时此刻， 我的心情不是别人所能理解的，土豪，请我看场《{$name}》呗？',
'movie_recommend_message' => '看完《{$name}》， 我和我的小伙伴们都惊呆了！ GO！ GO! GO! ',
'movie_index_page_size' => 20,
'movie_mark_interested_sns_message' => '感觉《{$name}》会挺好看的～～～，小伙伴们都去瞅瞅吧。',
'movie_mark_watched_sns_message' => '看完了《{$name}》，小伙伴们也去感受感受吧。',
'movie_mark_ignored_sns_message' => '《{$name}》貌似不好看的样子～～～',
    
    
    
    
'movie_mark_names'=>array('未标记','想看','已看','不喜欢'),
'system_name' => 'Lunome',
'movie_search_max_length' => 32,
'media_item_operation_waiting_image' => 'http://lunome-assets.qiniudn.com/image/waitting.gif',
'media_loader_loading_image' => 'http://lunome-assets.qiniudn.com/image/loadding.gif',
'media_list_page_size' => 20,
'max_auto_load_time_count' => 10,
'top_list_limitation' => 10,
'movie_detail_marked_user_list_page_size' => 10,
'movie_rate_max_score' => 10,
'movie_poster_file_type'=>array('image/png', 'image/jpeg'),
'movie_detail_poster_page_size' => 12,
'movie_detail_comment_page_size' => 5,
'movie_detail_classic_dialogue_page_size'=>10,
'movie_detail_character_page_size'=>5,
'movie_topic_suggested_size' => 10,
'movie_user_home_page_size' => 15,
'user_friend_index_page_size'=>10,
'user_friend_search_result_page_size'=>10,
'web_master_email'=>'michaelluthor@163.com',
'user_interaction_max_friend_count' => 10,
'user_friend_max_count' => 100,
);