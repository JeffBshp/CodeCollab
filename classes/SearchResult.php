<?php
class SearchResult {
	public static function generateResults($query, $option = "score") {
		$data = Database::getInstance();


		/*
		 * option = 0 : sort by score
		 * option = 1 : sort by date
		 */

		if($option == "date") {
			$data->query('SELECT Post.*, COUNT(Promotion.post_id) AS promotions
							FROM Post LEFT JOIN Promotion ON Post.id = Promotion.post_id
							LEFT JOIN User ON Post.user_id = User.id
							WHERE Post.title LIKE ? AND User.posts_visible = TRUE
							GROUP BY Post.id
							ORDER BY Post.post_date DESC', array('%'.$query.'%'));
		} else {
			$data->query('SELECT Post.*, COUNT(Promotion.post_id) AS promotions
							FROM Post LEFT JOIN Promotion ON Post.id = Promotion.post_id
							LEFT JOIN User ON Post.user_id = User.id
							WHERE Post.title LIKE ? AND User.posts_visible = TRUE
							GROUP BY Post.id
							ORDER BY promotions DESC', array('%'.$query.'%'));
		}
		
		$results = array();

		if($data->count()) {
			foreach($data->results() as $result) {
				array_push($results, self::formatResult($result));
			}
		} else {
			return array("NO RESULTS FOUND");
		}
		

		return $results;
	}

	private static function formatResult($result) {
		$data = Database::getInstance();
		$data->query('SELECT username FROM User WHERE id = ?', array($result->user_id));
		$username = $data->first()->username;
		$date = new DateTime($result->post_date);
		$date = $date->format('F d, Y \a\t h:ia');
		
		return ("<div class='post clearfix'>
						<div class='info'>
							<div class='score' title='Score'>" . $result->promotions . "</div>
							<div class='title'><a href='post.php?id=" . $result->id . "'>" . $result->title . "</a></div>
							<div class='author'><a href='profile.php?user=" . $username . "'>" . $username . "</a></div>
							<div class='date'>". $date ."</div>
						</div>
					</div>");
	}
}
?>