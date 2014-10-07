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
use X\Module\Lunome\Model\MovieUserWatchedModel;
use X\Module\Lunome\Model\MovieUserIgnoredModel;
use X\Module\Lunome\Model\MovieUserInterestedModel;
use X\Service\XDatabase\Core\Exception as DBException;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;

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
        $mark = $this->getModelNameByMovieMarkName($mark);
        $count = $mark::model()->count(array('account_id'=>$this->getCurrentUserId()));
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
        
        /* Delete old marks */
        $markModel = MovieUserIgnoredModel::model()->findByAttribute($condition);
        (null === $markModel) ? null : $markModel->delete();
        $markModel = MovieUserInterestedModel::model()->findByAttribute($condition);
        (null === $markModel) ? null : $markModel->delete();
        $markModel =MovieUserWatchedModel::model()->findByAttribute($condition);
        (null === $markModel) ? null : $markModel->delete();
        
        /* Add new Mark */
        $mark = $this->getModelNameByMovieMarkName($mark);
        $mark = new $mark();
        $mark->account_id = $this->getCurrentUserId($condition);
        $mark->movie_id = $movieId;
        $mark->save();
    }
    
    public function unmarkMovie( $movieId, $mark ) {
        $condition = array('account_id'=>$this->getCurrentUserId(), 'movie_id'=>$movieId);
        $mark = $this->getModelNameByMovieMarkName($mark);
        $mark = $mark::model()->findByAttribute($condition);
        $mark->delete();
    }
    
    private function getCurrentUserId() {
        return 'DEMO-ACCOUNT-ID';
    }
    
    private function getModelNameByMovieMarkName($mark) {
        return sprintf('\\X\\Module\\Lunome\\Model\\MovieUser%sModel', ucfirst($mark));
    }
    
    private function getMarkedMovieCondition($mark) {
        $mark = $this->getModelNameByMovieMarkName($mark);
        $markedMovieCondition = array('movie_id'=>new SQLExpression('movies.id'), 'account_id'=>$this->getCurrentUserId());
        $markedMovieActiveColumn = array('movie_id');
        $markCondition = $mark::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $basicCondition = ConditionBuilder::build()->exists($markCondition);
        return $basicCondition;
    }
    
    private function getUnmarkedMovieCondition() {
        /* Generate basic condition to ignore marked movies. */
        $markedMovieCondition = array('movie_id'=>new SQLExpression('movies.id'), 'account_id'=>$this->getCurrentUserId());
        $markedMovieActiveColumn = array('movie_id');
        $watchedMovies = MovieUserWatchedModel::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $ignoredMovies = MovieUserIgnoredModel::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $interestedMovies = MovieUserInterestedModel::query()->activeColumns($markedMovieActiveColumn)->find($markedMovieCondition);
        $basicCondition = ConditionBuilder::build()
        ->notExists($watchedMovies)
        ->andAlso()
        ->notExists($ignoredMovies)
        ->andAlso()
        ->notExists($interestedMovies);
        return $basicCondition;
    }
    
    const MARK_INTERESTED   = 'interested';
    const MARK_WATCHED      = 'watched';
    const MARK_IGNORED      = 'ignored';
}