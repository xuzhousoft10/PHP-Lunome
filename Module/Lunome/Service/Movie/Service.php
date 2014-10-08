<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Movie;

/**
 * Use statement
 */
use X\Module\Lunome\Util\Exception;
use X\Module\Lunome\Model\MovieModel;
use X\Module\Lunome\Model\MoviePosterModel;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Lunome\Model\MovieUserMarkModel;

/**
 * The service class
 */
class Service extends \X\Core\Service\XService {
    public function getUnmarkedMovies($condition=array(), $length=0, $position=0) {
        $basicCondition = $this->getUnmarkedMovieCondition();
        $movies = MovieModel::model()->findAll($basicCondition, $length, $position);
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = $movie->toArray();
        }
        return $movies;
    }
    
    public function getMarkedMovies( $mark, $length=0, $position=0 ) {
        $basicCondition = $this->getMarkedMovieCondition($mark);
        $movies = MovieModel::model()->findAll($basicCondition, $length, $position);
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = $movie->toArray();
        }
        return $movies;
    }
    
    public function countUnmarked() {
        $basicCondition = $this->getUnmarkedMovieCondition();
        $count = MovieModel::model()->count($basicCondition);
        return $count;
    }
    
    public function countMarked( $mark ) {
        $condition = array('account_id'=>$this->getCurrentUserId(),'type'=>$mark);
        $count = MovieUserMarkModel::model()->count($condition);
        return $count;
    }
    
    public function getPoster( $movieId ) {
        $poster = MoviePosterModel::model()->findByAttribute(array('movie_id'=>$movieId));
        if ( null === $poster ) {
            throw new Exception("Can not find poster $movieId");
        }
        
        $content = base64_decode($poster->data);
        if ( false === $content ) {
            throw new Exception("Bad poster : $movieId");
        }
        
        return $content;
    }
    
    public function markMovie( $movieId, $mark ) {
        $condition = array('account_id'=>$this->getCurrentUserId(), 'movie_id'=>$movieId);
        MovieUserMarkModel::model()->deleteAllByAttributes($condition);
        
        $mark = $this->convertMarkNameToMarkValue($mark);
        $markModel = new MovieUserMarkModel();
        $markModel->account_id = $this->getCurrentUserId();
        $markModel->movie_id = $movieId;
        $markModel->type = $mark;
        $markModel->save();
    }
    
    public function unmarkMovie( $movieId, $mark ) {
        $mark = $this->convertMarkNameToMarkValue($mark);
        $condition = array('account_id'=>$this->getCurrentUserId(), 'movie_id'=>$movieId, 'type'=>$mark);
        MovieUserMarkModel::model()->deleteAllByAttributes($condition);
    }
    
    private function getCurrentUserId() {
        return 'DEMO-ACCOUNT-ID';
    }
    
    private function getMarkedMovieCondition($mark) {
        $mark = $this->convertMarkNameToMarkValue($mark);
        $markedMovieCondition = array(
            'movie_id'   => new SQLExpression('movies.id'), 
            'account_id' => $this->getCurrentUserId(),
            'type'       => $mark
        );
        $markCondition = MovieUserMarkModel::query()->activeColumns(array('movie_id'))->find($markedMovieCondition);
        $basicCondition = ConditionBuilder::build()->exists($markCondition);
        return $basicCondition;
    }
    
    private function getUnmarkedMovieCondition() {
        /* Generate basic condition to ignore marked movies. */
        $markedMovieCondition = array('movie_id'=>new SQLExpression('movies.id'), 'account_id'=>$this->getCurrentUserId());
        $markedMovieActiveColumn = array('movie_id');
        $markedMovies = MovieUserMarkModel::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $basicCondition = ConditionBuilder::build()->notExists($markedMovies);
        return $basicCondition;
    }
    
    const MARK_INTERESTED   = 1;
    const MARK_WATCHED      = 2;
    const MARK_IGNORED      = 3;
    
    const MARK_NAME_UNMARKED    = 'unmarked';
    const MARK_NAME_INTERESTED  = 'interested';
    const MARK_NAME_WATCHED     = 'watched';
    const MARK_NAME_IGNORED     = 'ignored';
    
    private function convertMarkNameToMarkValue( $name ) {
        $map = array(
            self::MARK_NAME_UNMARKED    => 0,
            self::MARK_NAME_INTERESTED  => self::MARK_INTERESTED,
            self::MARK_NAME_WATCHED     => self::MARK_WATCHED,
            self::MARK_NAME_IGNORED     => self::MARK_IGNORED,
        );
        return $map[$name];
    }
}