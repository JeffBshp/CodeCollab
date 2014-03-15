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
							WHERE Post.title LIKE ?
							GROUP BY Post.id
							ORDER BY Post.post_date DESC', array('%'.$query.'%'));
		} else {
			$data->query('SELECT Post.*, COUNT(Promotion.post_id) AS promotions
							FROM Post LEFT JOIN Promotion ON Post.id = Promotion.post_id
							WHERE Post.title LIKE ?
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

	private function formatResult($result) {
		$data = Database::getInstance();

		$data->query('SELECT id, username FROM User WHERE id = ?', array($result->user_id));
		$userid = $data->first()->id;
		$username = $data->first()->username;

		$title = '<h3><a href="./post.php?id=' . $result->id . '">' . $result->title . '</a></h3> <b style="margin-left: 10px;">'. $result->promotions .'</b><br />';
		$author = '<em>Author: <a href="./profile.php?user='. $userid .'">'. $username .'</a></em><br /><hr />';
		return $title . $author;
	}
}
?>